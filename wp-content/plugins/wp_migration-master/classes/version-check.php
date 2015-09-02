<?php
defined( 'WPINC' ) or die;

class GD_Migrate_Version_Check extends WP_Stack_Plugin {
	public static $instance;

	public function __construct() {
		self::$instance = $this;
		$this->hook( 'init' );
	}

	public function passes() {
		return 'supported' === $this->get_status();
	}

	public static function get_status() {
		if ( is_multisite() ) {
			return 'multisite_not_supported';
		} elseif ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {
			return 'version_too_low';
		} else {
			return 'supported';
		}
	}

	public function init() {
		if ( ! $this->passes() ) {
  		if ( current_user_can( 'activate_plugins' ) ) {
				$this->hook( 'admin_init' );
				$this->hook( 'admin_notices' );
			}
		}
	}

	public function admin_init() {
		deactivate_plugins( plugin_basename( GD_MIGRATE_PLUGIN_FILE ) );
	}

	public function admin_notices() {
		switch( $this->get_status() ) {
			case 'version_too_low':
				echo '<div class="updated error"><p>' . __('The Managed WordPress Migration plugin requires WordPress 3.0 or higher. Update your WordPress install and try again.', 'gd-migrate' ) . '</p></div>';
				break;
			case 'multisite_not_supported':
				echo '<div class="updated error"><p>' . __('The Managed WordPress Migration plugin doesn’t support multi-site WordPress installs yet. If you’re migrating multiple sites, contact support for help.', 'gd-migrate' ) . '</p></div>';
		}
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}
