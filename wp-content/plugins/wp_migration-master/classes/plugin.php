<?php
defined( 'WPINC' ) or die;

class GD_Migrate_Plugin extends WP_Stack_Plugin {
	public static $instance;
	protected $_POST = false;
	const COMMAND_VAR = 'gd-migrate';
	const CALLBACK_VAR = 'gd-callback';

	public function __construct() {
		self::$instance = $this;
		$this->hook( 'init' );
	}

	public function init() {
		global $wp;
		$wp->add_query_var( self::COMMAND_VAR  );
		$wp->add_query_var( self::CALLBACK_VAR );
		$this->hook( 'template_redirect', -PHP_INT_MAX);
	}

	public function get_post( $key, $default = null ) {
		// Run stripslashes_deep() once
		if ( false === $this->_POST ) {
			$this->_POST = stripslashes_deep( $_POST );
		}
		if ( isset( $this->_POST[$key] ) ) {
			return $this->_POST[$key];
		} else {
			return $default;
		}
	}

	public function json_error( $error ) {
		if( function_exists( 'wp_send_json_success' ) ) {
			return wp_send_json_success( compact( 'error' ) );
		} else {
			$this->send_json_success(  compact( 'error' ) );
		}
	}

	public function get_nonce( $action = null ) {
		// Make sure an action name was passed in
		if ( is_null( $action ) ) {
			$this->json_error( 'action_parameter_required' );
		}
		if ( ! is_user_logged_in() ) {
			$this->json_error( 'not_logged_in' );
		} else {
			$data = array( 'nonce' => wp_create_nonce( $action) );
			if( function_exists( 'wp_send_json_success' ) ) {
				wp_send_json_success( $data );
			} else {
				$this->send_json_success( $data );
			}
		}
	}

	public function create_user( $nonce ) {
		// Check capabilities
		if ( ! current_user_can( 'create_users' ) ) {
			$this->json_error( 'capability_failure' );
		}
		
		// Check the nonce
		if ( ! wp_verify_nonce( $nonce, 'create-user' ) ) {
			$this->json_error( 'nonce_failure' );
		}
		
		// Securely and dynamically determine username
		$username = 'managed-wp-migration-' . substr( wp_hash( '#$%^managed-wp-username#$%^' ), 0, 8 );
		
		// See if the user already exists
		$user = get_user_by( 'login', $username );
		$user_email = get_user_by( 'email', 'noreply@secureserver.net' );
		// CHECK IF THESE USERS ARE THE SAME?
		$already_existed = (bool) $user || (bool) $user_email;
		
		if ( $user_email instanceof WP_User ) {
			$user = $user_email;
		}
		
		if( ! function_exists( 'wp_create_user' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/admin.php');
			include_once( ABSPATH . 'wp-includes/capabilities.php');
		}
		if ( ! $already_existed ) {
			// Create a user (note, GoDaddy does NOT have the password to this account)
			$user = wp_create_user( $username, wp_generate_password( 64, false ), 'noreply@secureserver.net' );
			if ( ! $user || is_wp_error( $user ) ) {
				$this->json_error( 'user_creation_failure' );
			}
			// Grab the newly created user
			$user = get_user_by( 'id', $user );
			if ( ! $user || is_wp_error( $user ) ) {
				$this->json_error( 'user_access_failure' );
			}
			// Bump the user up to administrator
			$user = new WP_User( $user->ID );
			$user->set_role( 'administrator' ); // This always succeeds
			if ( ! $user->has_cap( 'manage_options' ) ) {
				$this->json_error( 'capability_escalation_failure' );
			}
		}
		
		// Update the display name, if necessary
		$display_name = __( 'Managed WordPress Migration User', 'gd-migrate' );
		if ( $display_name !== $user->display_name || $display_name !== $user->first_name ) {
			$user->display_name = $display_name;
			$user->first_name = $display_name;
			$user->last_name = '';			
			wp_update_user( array ( 'ID' => $user->ID, 'display_name' => $display_name, 'first_name' => $user->first_name, 'last_name' => '' ) );			
		}
		
		// Generate a new access code
		$access_code = wp_generate_password( 32, false );
		
		// And store it hashed
		$hashed_access_code = wp_hash( $access_code );
		update_user_meta( $user->ID, '_godaddy_migration_code', $hashed_access_code );

		// Return the username and the access code
		if( function_exists( 'wp_send_json_success' ) ) {
			wp_send_json_success( compact( 'username', 'access_code' ) );
		} else {
			$this->send_json_success( compact( 'username', 'access_code' ) );
		}
	}

	public function validate_access_code() {
		$user = get_user_by( 'login',  $this->get_post( 'username' ));
		if ( ! $user ) {
			return false;
		}
		$hashed_access_code = get_user_meta( $user->ID, '_godaddy_migration_code', true );
		return $hashed_access_code === wp_hash( $this->get_post( 'access_code' ) );
	}

	public function get_manifest() {
		global $wpdb;

		if ( ! $this->validate_access_code() ) {			
			$this->json_error( 'invalid_username_or_access_code' );
		}
		
		$dir = new RecursiveDirectoryIterator( ABSPATH );
		$filtered = new GD_WordPress_File_Iterator_Filter( $dir );
		$iterator = new RecursiveIteratorIterator( $filtered );

		$raw_files = $files = array();
		foreach ( $iterator as $filename => $item ) {
			$raw_files[] = $filename;
		}

		$abspath = ABSPATH;
		if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
			$abspath = str_replace( '/', '\\', $abspath );
		}
		$abspath_regex = '#^' . preg_quote( $abspath, '#' ) . '#';
		foreach ( $raw_files as $file ) {
			if ( is_file( $file ) && is_readable( $file ) ) {
				$files[ utf8_encode( preg_replace( $abspath_regex, '', $file ) ) ] = @filesize( $file );
			}
		}
		unset( $raw_files );

		// Build up some site info:
		$misc = array(
			'abspath' => $abspath,
			'table_prefix' => $GLOBALS['table_prefix'],
		);

		// Send information about the database:
		$prefixed_tables = $wpdb->get_col( $wpdb->prepare( "SHOW TABLES LIKE %s;", $misc['table_prefix'] . '%' ) );
		$db = array(
			// Yeah, MySQL doesn't support NOT LIKE for SHOW TABLES. ??? Okay.
			'prefixed_tables' => $prefixed_tables,
			'other_tables' => array_diff( $wpdb->get_col( "SHOW TABLES;" ), $prefixed_tables ),
		);

		if( function_exists( 'wp_send_json_success' ) ) {
			wp_send_json_success( compact( 'misc', 'db', 'files' ) );
		} else {
			$this->send_json_success( compact( 'misc', 'db', 'files' ) );
		}
	}

	public function db_backup( $tables = array(), $segment = 0, $file_name = null ) {
		if ( ! $this->validate_access_code() ) {
			$this->json_error( 'invalid_username_or_access_code' );
		}
		$backup = new GoDaddy_WPDB_Backup;
		if ( $backup->verify_backup_directory() ) {
			$result = $backup->db_backup( $tables, $segment, $file_name );
			if ( $result->success ) {
				if( function_exists( 'wp_send_json_success' ) ) {
					wp_send_json_success( $result->data );
				} else {
					$this->send_json_success(  $result->data );
				}
			} else {
				$this->json_error( $result->error );
			}
		} else {
			$this->json_error( 'dump_failed' );
		}
	}

	public function template_redirect() {
		$cmd = get_query_var( self::COMMAND_VAR );		
		$callback = get_query_var( self::CALLBACK_VAR );
		if ( $cmd ) {
			// Uncache - otherwise the output can be unexpected.
			wp_cache_flush();

			// Don't report errors -- they break JSON output
			error_reporting( 0 );

			// Don't call any shutdown hooks -- they may break JSON output
			register_shutdown_function( create_function( '', 'die();' ) );

			switch( $cmd ) {
				case 'create-user' :
					$this->create_user( $this->get_post( 'nonce' ) );
					break;
				case 'get-nonce' :
					$this->get_nonce( $this->get_post( 'action' ) );
					break;
				case 'get-manifest' :
					$this->get_manifest();
					break;
				case 'db-backup' :
					$this->db_backup( json_decode( $this->get_post( 'tables', '[]' ) ), intval( $this->get_post( 'segment', 0 ) ), $this->get_post('file_name', null ) );
			}

			// Start the output buffer
			@ob_start();

			var_dump( $cmd );

			// Don't output the buffer -- it may break JSON output
			@ob_end_clean();

			// Sepaku
			die();
		}
	}

	public function send_json_success ( $data = null ) {
		$response = array( 'success' => true );
		if ( isset( $data ) ) {
			$response['data'] = $data;
		}
		$this->send_json( $response );
	}

	private function send_json( $response ) {
		if ( version_compare( PHP_VERSION, '5.4.0' ) < 0 ) {
			echo json_encode( $response );
		} else {
			echo json_encode( $response, JSON_PRETTY_PRINT);
		}
	}
}
