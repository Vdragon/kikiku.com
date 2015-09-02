<?php
/*
Copyright 2013 GoDaddy

Based on "WordPress Database Backup" by Austin Matzko:

Copyright 2013  Austin Matzko  (email : austin at pressedcode.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
*/

defined( 'WPINC' ) or die;

class GoDaddy_WPDB_Backup {

	var $backup_complete = false;
	var $backup_file = '';
	var $backup_filename;
	var $basename;
	var $page_url;
	var $referer_check_key;
	var $version = '2.1.5-alpha';

	function __construct() {
		global $table_prefix, $wpdb;

		$rand = substr( wp_hash( DB_PASSWORD ), 0, 16 );
		global $wpdbb_content_dir, $wpdbb_content_url;
		$wpdbb_content_dir = ( defined('WP_CONTENT_DIR') ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
		$wpdbb_content_url = ( defined('WP_CONTENT_URL') ) ? WP_CONTENT_URL : get_option('siteurl') . '/wp-content';

		if ( ! defined('WP_BACKUP_DIR') ) {
			define('WP_BACKUP_DIR', $wpdbb_content_dir . '/backup-' . $rand . '/');
		}

		if ( ! defined('WP_BACKUP_URL') ) {
			define( 'WP_BACKUP_URL', $wpdbb_content_url . '/backup-' . $rand . '/' );
		}

		if ( ! defined('ROWS_PER_SEGMENT') ) {
			define( 'ROWS_PER_SEGMENT', 2000 );
		}

		$datum = date( "Ymd_B" );
		$this->backup_filename = DB_NAME . "_$table_prefix$datum." . substr( wp_hash( DB_NAME . "_$table_prefix$datum" ), 0, 8 ) . ".sql";

		$this->backup_dir = trailingslashit( WP_BACKUP_DIR );
		$this->basename = 'wp-db-backup';

		$this->referer_check_key = $this->basename . '-download_' . DB_NAME;
	}

	function backup_fragment( $table, $segment, $filename ) {
		global $table_prefix, $wpdb;

		echo "$table:$segment:$filename";

		if ( $table == '' ) {
			$msg = __('Creating backup file...','wp-db-backup');
		} else {
			if ( $segment == -1 ) {
				$msg = sprintf( __( 'Finished backing up table \\"%s\\".','wp-db-backup' ), $table );
			} else {
				$msg = sprintf( __( 'Backing up table \\"%s\\"...','wp-db-backup' ), $table );
			}
		}

		if ( is_writable( $this->backup_dir ) ) {
			$this->fp = $this->open( $this->backup_dir . $filename, 'a' );
			if ( ! $this->fp ) {
				$this->error(__('Could not open the backup file for writing!','wp-db-backup'));
				$this->error(array('loc' => 'frame', 'kind' => 'fatal', 'msg' =>  __('The backup file could not be saved.  Please check the permissions for writing to your backup directory and try again.','wp-db-backup')));
			} else {
				if ( $table == '' ) {
					// Begin new backup of MySql
					$this->stow( "# " . __('WordPress MySQL database backup','wp-db-backup') . "\n"   );
					$this->stow( "#\n"                                                                );
					$this->stow( "# " . sprintf( 'Generated: %s', date( "l j. F Y H:i T" ) ) . "\n"   );
					$this->stow( "# " . sprintf( 'Hostname: %s', DB_HOST) . "\n"                      );
					$this->stow( "# " . sprintf( 'Database: %s', $this->backquote( DB_NAME ) ) . "\n" );
					$this->stow( "# --------------------------------------------------------\n"       );
				} else {
					if ( $segment == 0 ) {
						// Increase script execution time-limit to 15 min for every table.
						if ( !ini_get( 'safe_mode' ) ) {
							@set_time_limit( 15 * 60 );
						}
						// Create the SQL statements
						$this->stow( "# --------------------------------------------------------\n"   );
						$this->stow( "# " . sprintf( 'Table: %s', $this->backquote( $table ) ) . "\n" );
						$this->stow( "# --------------------------------------------------------\n"   );
					}
					$this->backup_table( $table, $segment );
				}
			}
		} else {
			$this->error( array( 'kind' => 'fatal', 'loc' => 'frame', 'msg' => __( 'The backup directory is not writeable!  Please check the permissions for writing to your backup directory and try again.','wp-db-backup' ) ) );
		}

		if ( $this->fp )
			$this->close( $this->fp );
	}

	/**
	 * Better addslashes for SQL queries.
	 * Taken from phpMyAdmin.
	 */
	function sql_addslashes( $a_string = '', $is_like = false ) {
		if ( $is_like ) {
			$a_string = str_replace( '\\', '\\\\\\\\', $a_string );
		} else {
			$a_string = str_replace( '\\', '\\\\', $a_string );
		}
		return str_replace( '\'', '\\\'', $a_string );
	}

	/**
	 * Add backquotes to tables and db-names in
	 * SQL queries. Taken from phpMyAdmin.
	 */
	function backquote( $a_name ) {
		if ( !empty( $a_name ) && $a_name != '*' ) {
			if ( is_array( $a_name ) ) {
				$result = array();
				reset( $a_name );
				while( list( $key, $val ) = each( $a_name ) )
					$result[$key] = '`' . $val . '`';
				return $result;
			} else {
				return '`' . $a_name . '`';
			}
		} else {
			return $a_name;
		}
	}

	function open( $filename = '', $mode = 'w' ) {
		if ( '' === $filename ) {
			return false;
		}
		$fp = @fopen( $filename, $mode );
		return $fp;
	}

	function close( $fp ) {
		fclose( $fp );
	}

	/**
	 * Write to the backup file
	 * @param string $query_line the line to write
	 * @return null
	 */
	function stow( $line ) {
		if ( false === @fwrite( $this->fp, $line ) ) {
			$this->error( __( 'There was an error writing a line to the backup script:','wp-db-backup') . '  ' . $line . '  ' . $php_errormsg );
		}
	}

	/**
	 * Taken partially from phpMyAdmin and partially from
	 * Alain Wolf, Zurich - Switzerland
	 * Website: http://restkultur.ch/personal/wolf/scripts/db_backup/
	 *
	 * Modified by Scott Merrill (http://www.skippy.net/)
	 * to use the WordPress $wpdb object
	 * @param string $table
	 * @param null|int $segment Null segment means "grab the whole table in one go"
	 *                          Integer of zero or greater means "take this segment" (zero-indexed)
	 *                          Integer of -1 means "close it off"
	 * @return void
	 */
	function backup_table( $table, $segment = null ) {
		global $wpdb;
		$table_data = array();
		$table_out = preg_replace( '#^' . $GLOBALS['table_prefix'] . '#', 'GODADDY_WPDB_TABLE_PREFIX_', $table );

		$table_structure = $wpdb->get_results( "DESCRIBE $table" );
		if ( ! $table_structure ) {
			$this->error( __( 'Error getting table details','wp-db-backup' ) . ": $table" );
			return false;
		}

		/*
			We're making a SQL sandwich here, consisting of:

				1. DROP TABLE / CREATE TABLE statements
				2. Row inserts (one or more rounds of this)
				3. A closing comment to seal in the flavor

				Let's begin.
		*/

		// =======================
		//  1. TOP SLICE OF BREAD
		// =======================
		if ( ( $segment === null ) || ( $segment == 0 ) ) {
			$this->stow( "# --------------------------------------------------------\n" );
			$this->stow( "# " . sprintf( __( 'Table: %s','wp-db-backup'), $this->backquote( $table ) ) . "\n" );
			$this->stow( "# --------------------------------------------------------\n" );

			// Disable foreign key checks and wrap in a transaction
			$this->stow( "SET FOREIGN_KEY_CHECKS=0;\n " );
			$this->stow( "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n" );
			$this->stow( "SET AUTOCOMMIT=0;\n" );
			$this->stow( "START TRANSACTION;" );

			// Add SQL statement to drop existing table
			$this->stow( "\n\n" );
			$this->stow( "#\n" );
			$this->stow( "# " . sprintf( __( 'Delete any existing table %s', 'wp-db-backup' ), $this->backquote( $table ) ) . "\n" );
			$this->stow( "#\n" );
			$this->stow( "\n" );
			$this->stow( "DROP TABLE IF EXISTS " . $this->backquote( $table_out ) . ";\n" );

			// Table structure
			// Comment in SQL-file
			$this->stow( "\n\n" );
			$this->stow( "#\n" );
			$this->stow( "# " . sprintf( __( 'Table structure of table %s','wp-db-backup' ), $this->backquote( $table ) ) . "\n" );
			$this->stow( "#\n" );
			$this->stow( "\n" );

			$create_table = $wpdb->get_results( "SHOW CREATE TABLE $table", ARRAY_N );
			if ( false === $create_table ) {
				$err_msg = sprintf( __( 'Error with SHOW CREATE TABLE for %s.','wp-db-backup' ), $table );
				$this->error( $err_msg );
				$this->stow( "#\n# $err_msg\n#\n" );
			}
			$this->stow( str_replace( $table, $table_out, $create_table[0][1] ) . ' ;' );

			if ( false === $table_structure ) {
				$err_msg = sprintf( __( 'Error getting table structure of %s','wp-db-backup' ), $table );
				$this->error( $err_msg );
				$this->stow( "#\n# $err_msg\n#\n" );
			}

			// Comment in SQL-file
			$this->stow( "\n\n" );
			$this->stow( "#\n" );
			$this->stow( '# ' . sprintf( __( 'Data contents of table %s','wp-db-backup' ), $this->backquote( $table ) ) . "\n" );
			$this->stow( "#\n" );
		}

		// =============
		//  2. THE MEAT
		// =============
		if ( ( $segment === null ) || ( $segment >= 0 ) ) {
			$defs = array();
			$ints = array();
			foreach ( $table_structure as $struct ) {
				if ( ( 0 === strpos( $struct->Type, 'tinyint' ) ) ||
					( 0 === strpos( strtolower( $struct->Type ), 'smallint' ) ) ||
					( 0 === strpos( strtolower( $struct->Type ), 'mediumint' ) ) ||
					( 0 === strpos( strtolower( $struct->Type ), 'int' ) ) ||
					( 0 === strpos( strtolower( $struct->Type ), 'bigint' ) ) ) {
						$defs[strtolower( $struct->Field )] = ( null === $struct->Default ) ? 'NULL' : $struct->Default;
						$ints[strtolower( $struct->Field )] = "1";
				}
			}

			if ( $segment === null ) {
				$row_start = 0;
				$row_inc = ROWS_PER_SEGMENT;
			} else {
				$row_start = $segment * ROWS_PER_SEGMENT;
				$row_inc = ROWS_PER_SEGMENT;
			}

			do {
				if ( ! ini_get( 'safe_mode' ) ) {
					@set_time_limit( 15 * 60 );
					@ini_set('memory_limit', '256M');
				}
				$table_data = $wpdb->get_results( "SELECT * FROM $table LIMIT {$row_start}, {$row_inc}", ARRAY_A );

				$entries = 'INSERT IGNORE INTO ' . $this->backquote( $table_out ) . ' VALUES (';
				//    \x08\\x09, not required
				$search = array( "\x00", "\x0a", "\x0d", "\x1a" );
				$replace = array( '\0', '\n', '\r', '\Z' );
				if ( $table_data ) {
					foreach ( $table_data as $row ) {
						$values = array();
						foreach ( $row as $key => $value ) {
							if ( isset( $ints[strtolower( $key )] ) && $ints[strtolower( $key )] ) {
								// make sure there are no blank spots in the insert syntax,
								// yet try to avoid quotation marks around integers
								$value = ( null === $value || '' === $value ) ? $defs[strtolower( $key )] : $value;
								$values[] = ( '' === $value ) ? "''" : $value;
							} else {
								$values[] = "'" . str_replace( $search, $replace, $this->sql_addslashes( $value ) ) . "'";
							}
						}
						$this->stow( " \n" . $entries . implode( ', ', $values ) . ');' );
					}
					$row_start += $row_inc;
				}
			} while ( ( count( $table_data ) > 0 ) && ( $segment === null ) );
		}

		// ==========================
		//  2. BOTTOM SLICE OF BREAD
		// ==========================
		if ( ( $segment === null ) || ( $segment < 0 ) ) {
			$this->stow( "\n" );
			$this->stow( "#\n" );
			$this->stow( "# " . sprintf( __( 'End of data contents of table %s','wp-db-backup' ), $this->backquote( $table ) ) . "\n" );
			$this->stow( "# --------------------------------------------------------\n" );

			$this->stow( "SET FOREIGN_KEY_CHECKS=1;\n" );
			$this->stow( "COMMIT;\n" );

			$this->stow( "\n" );
		}

		return count( $table_data );
	}

	function fail( $error ) {
		$result = new stdClass;
		$result->success = false;
		$result->error = $error;
		return $result;
	}

	function succeed( $data ) {
		$result = new stdClass;
		$result->success = true;
		$result->data = (object) $data;
		return $result;
	}
	
	function db_backup( $tables, $segment = -1, $file ) {
		global $table_prefix, $wpdb;		

		$file = null === $file ? $this->backup_filename : preg_replace( '#[^a-z0-9._]#', '', $file );

		if ( -1 === $segment ) {
			$file =  $this->backup_filename;
		}

		$this->backup_filename = $file;

		if ( is_writable( $this->backup_dir ) ) {
			// We write this for safety, in case directory indexing is on
			// This file MUST be present
			if ( ! file_exists( $this->backup_dir . 'index.php' ) ) {
				@touch( $this->backup_dir . 'index.php' );
				if ( ! file_exists( $this->backup_dir . 'index.php' ) ) {
					// Some hosts do not allow us to write php files, trying an html index
					@touch( $this->backup_dir . 'index.html' );
					if ( ! file_exists( $this->backup_dir . 'index.html' ) ) {
						return $this->fail( 'index_file_unwritable' );
					}
				}
			}
			$this->fp = $this->open( $this->backup_dir . $file, 'a' );
			if ( !$this->fp ) {
				return $this->fail( 'backup_file_unwritable' );
			}
		} else {
			return $this->fail( 'backup_directory_unwritable' ) ;
		}
				
		if ( -1 === $segment ) {
			// Begin new backup
			$this->stow( "# " . __('WordPress MySQL database backup','wp-db-backup' ) . "\n" );
			$this->stow( "#\n" );
			$this->stow( "# " . sprintf( __( 'Generated: %s', 'wp-db-backup' ), date( "l j. F Y H:i T" ) ) . "\n" );
			$this->stow( "# " . sprintf( __( 'Hostname: %s', 'wp-db-backup' ), DB_HOST ) . "\n" );
			$this->stow( "# " . sprintf( __( 'Database: %s', 'wp-db-backup' ), $this->backquote( DB_NAME ) ) . "\n" );
			$this->stow( "# --------------------------------------------------------\n" );
			$segment = 0;
		}
		
		foreach ( $tables as $table ) {
			// Increase script execution time-limit to 15 min for every table.
			if ( ! ini_get( 'safe_mode' ) ) {
				@set_time_limit( 15 * 60 );
			}
			// Create the SQL statements
			$number_saved = $this->backup_table( $table, $segment );
			if ( $number_saved < ROWS_PER_SEGMENT )  {
				// We're done with this table
				// Wrap it up
				$this->backup_table( $table, -1 );
				// Remove it from the queue
				array_shift( $tables );
				// Start at the beginning for the next table
				$segment = 0;
				// if we saved fewer than 1/5 our limit, consider it a freebee and do another one
				if ( $number_saved < ( ROWS_PER_SEGMENT / 5 ) ) {
					continue;
				} else {
					// Get out of here, we have worked enough for one page load
					break;
				}
			} else {
				// More to do on this table
				$segment++;
				break;
			}
		}

		$done = count( $tables ) === 0;

		$this->close( $this->fp );
		$abspath_regex = '#^' . preg_quote( ABSPATH, '#' ) . '#';

		return $this->succeed( array(
			'done' => $done,
			'file' => preg_replace( $abspath_regex, '', $this->backup_dir ) . $this->backup_filename,
			'file_size' => filesize( $this->backup_dir . $this->backup_filename ),
			'file_name' => $this->backup_filename,
			'tables' => $tables,
			'segment' => $segment,
		) );
	}

	function verify_backup_directory() {
		$dir_perms = '0777';

		// the file doesn't exist and can't create it
		if ( ! file_exists( $this->backup_dir ) && ! @mkdir( $this->backup_dir ) ) {
			return false;
		} elseif ( !is_writable( $this->backup_dir ) && ! @chmod( $this->backup_dir, $dir_perms ) ) {
			return false;
		} else {
			$this->fp = $this->open( $this->backup_dir . 'test' );
			if ( $this->fp ) {
				$this->close( $this->fp );
				@unlink( $this->backup_dir . 'test' );
			} else {
				// the directory is not writable probably due to safe mode
				return false;
			}
		}

		if ( !file_exists( $this->backup_dir . 'index.php' ) ) {
			@touch( $this->backup_dir . 'index.php'  );
		}
		return true;
	}

	function deliver_backup( $filename = '', $delivery = 'http', $recipient = '', $location = 'main' ) {
		if ( '' == $filename ) {
			return false;
		}

		$diskfile = $this->backup_dir . $filename;
		$gz_diskfile = "{$diskfile}.gz";

		/**
		 * Try upping the memory limit before gzipping
		 */
		if ( function_exists( 'memory_get_usage' ) && ( (int) @ini_get( 'memory_limit' ) < 64 ) ) {
			@ini_set( 'memory_limit', '64M' );
		}

		if ( file_exists( $diskfile ) && empty( $_GET['download-retry'] ) ) {
			/**
			 * Try gzipping with an external application
			 */
			if ( file_exists( $diskfile ) && ! file_exists( $gz_diskfile ) ) {
				@exec( "gzip $diskfile" );
			}

			if ( file_exists( $gz_diskfile ) ) {
				if ( file_exists( $diskfile ) ) {
					unlink( $diskfile );
				}
				$diskfile = $gz_diskfile;
				$filename = "{$filename}.gz";

			/**
			 * Try to compress to gzip, if available
			 */
			} else {
				if ( function_exists( 'gzencode' ) ) {
					if ( function_exists( 'file_get_contents' ) ) {
						$text = file_get_contents( $diskfile );
					} else {
						$text = implode( "", file( $diskfile ) );
					}
					$gz_text = gzencode( $text, 9 );
					$fp = fopen( $gz_diskfile, "w" );
					fwrite( $fp, $gz_text );
					if ( fclose( $fp ) ) {
						unlink( $diskfile );
						$diskfile = $gz_diskfile;
						$filename = "{$filename}.gz";
					}
				}
			}
			/*
			 *
			 */
		} elseif ( file_exists( $gz_diskfile ) && empty( $_GET['download-retry'] ) ) {
			$diskfile = $gz_diskfile;
			$filename = "{$filename}.gz";
		}

		if ( 'http' == $delivery ) {
			if ( ! file_exists( $diskfile ) ) {
				if ( empty( $_GET['download-retry'] ) ) {
					$this->error( array( 'kind' => 'fatal', 'msg' => sprintf( __( 'File not found:%s', 'wp-db-backup' ), "&nbsp;<strong>$filename</strong><br />") . '<br /><a href="' . $this->page_url . '">' . __( 'Return to Backup', 'wp-db-backup' ) . '</a>' ) );
				} else {
					return true;
				}
			} elseif ( file_exists( $diskfile ) ) {
				header( 'Content-Description: File Transfer' );
				header( 'Content-Type: application/octet-stream' );
				header( 'Content-Length: ' . filesize( $diskfile ) );
				header( "Content-Disposition: attachment; filename=$filename" );
				$success = readfile( $diskfile );
				if ( $success ) {
					unlink( $diskfile );
				}
			}
		}
		return $success;
	}

	/**
	 * Check whether a file to be downloaded is
	 * surreptitiously trying to download a non-backup file
	 * @param string $file
	 * @return null
	 */
	function validate_file( $file ) {
		if ( ( false !== strpos( $file, '..' ) ) || ( false !== strpos( $file, './' ) ) || ( ':' == substr( $file, 1, 1 ) ) ) {
			$this->error( array( 'kind' => 'fatal', 'loc' => 'frame', 'msg' => __( "Cheatin' uh ?", 'wp-db-backup' ) ) );
		}
	}

	function error() {
		// Noop, for now
	}

}
