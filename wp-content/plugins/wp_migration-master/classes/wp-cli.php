<?php
/**
 * Imports a WordPress site into GoDaddy hosting
 *
 * @package wp-cli
 * @subpackage commands/third-party
 */
class GD_Migrate_Plugin_Import_Command extends WP_CLI_Command {
	protected $fs;
	protected $fs_method;
	protected $host;
	protected $user;
	protected $password;
	protected $remote_db_file;
	protected $manifest;
	protected $log_file = false;

	const CONFIG_BACKUP = 'IMPORT-BACKUP__wp-config.php';

	public function __construct() {
		// Add in an SSH2 fs method file reference
		add_filter( 'filesystem_method_file', array( $this, 'ssh2_fs_method_file'), 10, 2 );
	}

	public function ssh2_fs_method_file( $path, $method ) {
		if ( 'ssh2' === $method ) {
			return dirname(__FILE__) . '/ssh2/class-wp-filesystem-ssh2.php';
		} else {
			return $path;
		}
	}

	/**
	 * Imports a WordPress site into GoDaddy hosting
	 *
	 * ## OPTIONS
	 *
	 * --ftp-host=<host>
	 * : The FTP/SFTP host of the source site
	 *
	 * --ftp-user=<username>
	 * : The FTP/SFTP username of the source site
	 *
	 * --ftp-password=<password>
	 * : The FTP/SFTP password of the source site
	 *
	 * --manifest=<path>
	 * : Local filesystem path for an import manifest json file
	 *
	 * --remote-db=<path>
	 * : Remote filesystem path for the DB export file
	 *
	 * [--log-file=<log-file>]
	 * : The log file for a migration
	 * 
	 * ## EXAMPLES
	 *
	 *   wp gd-import --ftp-host=example.com --ftp-user=george.costanza --ftp-password=1tuck1no-tuck --manifest=/home/avandalay/manifest.json --remote-db=/del/boca/vista/backup-4325/wp_123.sql
	 *
	 * @synopsis --ftp-host=<host> --ftp-user=<username> --ftp-password=<password> --manifest=<path> --remote-db=<path> [--log-file=<log-file>]
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;

		$this->host = $assoc_args['ftp-host'];
		$this->user = $assoc_args['ftp-user'];
		$this->pass = $assoc_args['ftp-password'];
		$manifest = $assoc_args['manifest'];
		$this->remote_db_file = $assoc_args['remote-db'];

		if( isset($assoc_args['log-file']) ) {
			$this->log_file = $assoc_args['log-file'];
		}
		$this->manifest = json_decode( file_get_contents( $manifest ) );
		$this->fs = $this->get_ftp_connection();
		if ( is_wp_error( $this->fs->errors ) && $this->fs->errors->get_error_codes() ) {
			self::error( $this->fs->errors->get_error_message() );
		}

		self::line( 'Connected to remote FS with method: ' . $this->fs->method );
		$abspath = $this->find_abspath( $this->manifest->data->misc->abspath );
		self::line( 'Remote FS root path: ' . $abspath );
		$local_fs = $this->attempt_fs_connect( 'direct' );
		self::line( 'Connected to local FS with method: direct' );
		self::line( 'Local FS root path: ' . $local_fs->abspath() );

		if ( !$abspath ) {
			self::error( 'Cannot find remote path' );
		}

		// Loop through the files
		$reconnects = 10;
		foreach( $this->manifest->data->files as $file => $size ) {

			// Fix windows paths
			if ( isset( $this->manifest->data->misc->abspath[1]) && ':' === $this->manifest->data->misc->abspath[1] ) {
				$file = str_replace( '\\', '/', $file );
			}
			
			self::line( '• ' . $file );
			self::line( '  Downloading...' );
			$content = $this->fs->get_contents( $abspath . $file );
			if ( false === $content && $reconnects-- > 0 ) {
				self::line( '  Reconnecting ' );
				$this->fs->connect();
				$content = $this->fs->get_contents( $abspath . $file );
			}
			
			if ( false === $content ) {
				self::line( '  Not found...' );
			} else {
				// Now, make sure the directory exists
				$this->ensure_directory( dirname( $file ), $local_fs );
				self::line( '  Writing...' );
				if ( 'wp-config.php' === $file ) {
					$local_fs->put_contents( ABSPATH . self::CONFIG_BACKUP, $content );
				} else {
					$local_fs->put_contents( ABSPATH . $file, $content );
				}
			}
		}

		// Reconnect
		$this->fs->connect();
		
		// Merge in custom wp-config.php settings
		if ( $local_fs->is_file( ABSPATH . self::CONFIG_BACKUP ) ) {
			$old_wp_config = new GoDaddy_WP_Config_Alterations( $local_fs->get_contents( ABSPATH . self::CONFIG_BACKUP ) );
			$defines = $old_wp_config->get_defines();
			if ( $defines ) {
				$new_wp_config = $local_fs->get_contents( ABSPATH . 'wp-config.php' );
				// Strip off our leading PHP tag
				$new_wp_config = preg_replace( '#^<' . '\?php#', '', $new_wp_config, 1 );
				$temp_new_config = "<" . "?php\n\n// ===BEGIN_IMPORTED_DEFINES==\n";
				foreach ( $defines as $name => $contents ) {
					$temp_new_config .= "define( '{$name}', $contents );\n";
				}
				$temp_new_config .= "// ===END_IMPORTED_DEFINES===\n\n";
				$new_wp_config = $temp_new_config . $new_wp_config;
				$local_fs->put_contents( ABSPATH . 'wp-config.php', $new_wp_config );
			}
		}

		// Completed file transfer, we hope
		// Transfer DB file
		$file = $this->remote_db_file;

		// Fix windows paths
		if ( isset( $this->manifest->data->misc->abspath[1]) && ':' === $this->manifest->data->misc->abspath[1] ) {
			$file = str_replace( '\\', '/', $file );
		}
		
		self::line( '• Database File: ' . $file );
		if ( $this->fs->is_file( $abspath . $file ) ) {
			self::line( '  Downloading...' );
			$content = $this->fs->get_contents( $abspath . $file );
			// Now, make sure the directory exists
			$this->ensure_directory( dirname( $file ), $local_fs );

			// Swap out the table prefix
			$content = str_replace( 'GODADDY_WPDB_TABLE_PREFIX_', $GLOBALS['table_prefix'], $content );

			self::line( '  Writing...' );
			$local_fs->put_contents( ABSPATH . $file, $content );
			self::line( '  Importing...' );
			$this->import_db( ABSPATH . $file );
			self::line( '  Modifying...' );

			// Re-establish wpdb connection
			mysql_close( $wpdb->dbh );
			$wpdb->db_connect();

			// Do the swaps on table content that contains the table prefix
			if ( $this->manifest->data->misc->table_prefix !== $GLOBALS['table_prefix'] ) {
				$prefix = str_replace( '_', '\_', $this->manifest->data->misc->table_prefix );
				foreach ( array(
					$wpdb->options => array( 'option_name', 'option_id' ),
					$wpdb->usermeta => array( 'meta_key', 'umeta_id' ),
				) as $table => $column ) {
					$results = $wpdb->get_results( "SELECT * FROM $table WHERE {$column[0]} LIKE '{$prefix}%'", ARRAY_A );
					if ( $results ) {
						foreach ( $results as $result ) {
							// Unset the autoincrement column
							unset( $result[$column[1]] );
							// Replace the prefix
							$result[$column[0]] = preg_replace( '#^' . $this->manifest->data->misc->table_prefix . '#', $GLOBALS['table_prefix'], $result[$column[0]] );
							// Insert a new row (safer to leave the old row)
							$wpdb->insert( $table, $result );
						}
					}
				}
			}

			self::success( 'Done!' );
		} else {
			self::error( '  Not found...' );
		}
	}

	private function import_db( $file ) {
		WP_CLI::run_command( array( 'db', 'import', $file ), array() );
	}

	private function ensure_directory( $path, $fs ) {
		$exploded = explode( '/', $path );
		$built_path = '';
		foreach ( $exploded as $path_part ) {
			$built_path .= $path_part . '/';
			if ( ! $fs->exists( ABSPATH . $built_path ) ) {
				$fs->mkdir( ABSPATH . $built_path );
			}
		}
	}

	private function find_abspath( $raw_abspath) {
		$folder = $this->fs->find_folder( $raw_abspath );
		// Perhaps the FTP folder is rooted at the WordPress install, Check for wp-includes folder in root, Could have some false positives, but rare.
		if ( ! $folder && $this->fs->is_dir('/wp-includes') )
			$folder = '/';
		
		// Check to make sure it's the right folder, we've been fooled before.  Go up one level if it's not right
		if ( !$this->fs->is_dir( $folder . '/wp-includes' ) ) {
			$folder = untrailingslashit( $folder ) . '/../';
			if ( !$this->fs->is_dir( $folder . '/wp-includes' ) ) {
				$folder = false;
			}
		}

		return $folder;
	}

	private function get_ftp_connection() {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$methods = array();
		if ( extension_loaded('ftp') ) {
			$methods[] = 'ftpext';
		} elseif ( extension_loaded( 'sockets' ) || function_exists( 'fsockopen' ) ) {
			$methods[] = 'ftpsockets';
		}
		$methods[] = 'ssh2';

		foreach( $methods as $method ) {
			$fs = $this->attempt_fs_connect( $method );
			if ( ! is_wp_error( $fs->errors ) || ! $fs->errors->get_error_codes() ) {
				break;
			}
		}

		return $fs;
	}

	private function attempt_fs_connect( $method ) {
		global $wp_filesystem;
		// Back up the original
		$wp_filesystem_backup = $wp_filesystem;
		$this->fs_method = $method;
		add_filter( 'filesystem_method', array( $this, 'filesystem_method_override' ), 9999 );
		$fs_result = WP_Filesystem( array(
			'hostname' => $this->host,
			'username' => $this->user,
			'password' => $this->pass,
		) );
		remove_filter( 'filesystem_method', array( $this, 'filesystem_method_override' ), 9999 );
		$my_wp_filesystem = $wp_filesystem;
		$wp_filesystem = $wp_filesystem_backup;
		return $my_wp_filesystem;
	}

	public function filesystem_method_override() {
		return $this->fs_method;
	}

	static function help() {
		WP_CLI::line( <<<EOL
usage: wp gd-import --ftp-host=<host> --ftp-user=<username> --ftp-password=<password> --manifest=<path> --remote-db=<path> --log-file=<log-file>

	 --ftp-host                     The FTP/SFTP host of the source site
	 --ftp-user                     The FTP/SFTP username of the source site
	 --ftp-password                 The FTP/SFTP password of the source site
	 --manifest                     Local filesystem path for an import manifest json file
	 --remote-db                    Remote filesystem path for the DB export file
	 --log-file						The log file for a migration
	 
EOL
		);
	}
	
	/**
	 * Write line to log file, and WP_CLI
	 *
	 * @param string $message
	 */
	protected function line( $message = '' ) {
		if( $this->log_file ) {
			file_put_contents( $this->log_file, self::format_log_message( $message ), FILE_APPEND );
		}
		WP_CLI::line( $message );
	}

	/**
	 * Write success message to log file, and WP_CLI
	 *
	 * @param string $message
	 */
	protected function success( $message ) {
		if( $this->log_file ) {
			file_put_contents( $this->log_file, self::format_log_message( $message ), FILE_APPEND );
		}
		WP_CLI::success( $message );
}

	/**
	 * Write warning to log file, and WP_CLI
	 *
	 * @param string $message
	 */
	protected function warning( $message ) {
		if( $this->log_file ) {
			file_put_contents( $this->log_file, self::format_log_message( $message ), FILE_APPEND );
		}
		WP_CLI::warning( $message );	
	}

	/**
	 * Write error message to log file, and WP_CLI
	 *
	 * @param string $message
	 */
	protected function error( $message ) {
		if( $this->log_file ) {
			file_put_contents( $this->log_file, self::format_log_message( $message ), FILE_APPEND );
		}
		WP_CLI::error( $message );
	}
	
	private function format_log_message( $message ) {
		return sprintf( "[%s]: %s\n", date( 'Y-m-d H:i:s' ), $message );
	}
}

WP_CLI::add_command( 'gd-import', 'GD_Migrate_Plugin_Import_Command' );
