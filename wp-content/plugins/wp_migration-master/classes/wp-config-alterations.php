<?php

class GoDaddy_WP_Config_Alterations {
	protected $contents;
	const DEFINE_REGEX = '#define\s*\(\s*([\'"])([a-z0-9_]+)\1\s*,\s*([^;]+)\s*\)\s*;#ims';

	public function __construct( $contents ) {
		$this->contents = $contents;
	}

	public function get_defines() {
		preg_match_all( self::DEFINE_REGEX, $this->contents, $matches, PREG_SET_ORDER );
		$result = array();
		foreach ( $matches as $match ) {
			if ( ! in_array( $match[2], $this->disallowed_defines() ) ) {
				$result[$match[2]] = trim( $match[3] );
			}
		}
		return $result;
	}

	protected function disallowed_defines() {
		return array(
			'ABSPATH',
			'ADMIN_COOKIE_PATH',
			'ALTERNATE_WP_CRON',
			'AUTH_KEY',
			'AUTH_SALT',
			'CONCATENATE_SCRIPTS',
			'COOKIEPATH',
			'CUSTOM_USER_META_TABLE',
			'CUSTOM_USER_TABLE',
			'DB_CHARSET',
			'DB_COLLATE',
			'DB_HOST',
			'DB_NAME',
			'DB_PASSWORD',
			'DB_USER',
			'DISABLE_WP_CRON',
			'FORCE_SSL_ADMIN',
			'FORCE_SSL_LOGIN',
			'FS_CHMOD_DIR',
			'FS_CHMOD_FILE',
			'FS_METHOD',
			'FTP_BASE',
			'FTP_CONTENT_DIR',
			'FTP_HOST',
			'FTP_PASS',
			'FTP_PLUGIN_DIR',
			'FTP_PRIKEY',
			'FTP_PUBKEY',
			'FTP_SSL',
			'FTP_USER',
			'LOGGED_IN_KEY',
			'LOGGED_IN_SALT',
			'NOBLOGREDIRECT',
			'NONCE_KEY',
			'NONCE_SALT',
			'PLUGINS_COOKIE_PATH',
			'SAVEQUERIES',
			'SCRIPT_DEBUG',
			'SECURE_AUTH_KEY',
			'SECURE_AUTH_SALT',
			'SITECOOKIEPATH',
			'STYLE_DEBUG',
			'STYLESHEETPATH',
			'TEMPLATEPATH',
			'WP_ACCESSIBLE_HOSTS',
			'WP_ALLOW_MULTISITE',
			'WP_ALLOW_REPAIR',
			'WP_CACHE',
			'WP_CRON_LOCK_TIMEOUT',
			'WP_DEBUG',
			'WP_DEBUG_DISPLAY',
			'WP_DEBUG_LOG',
			'WP_HOME',
			'WP_HTTP_BLOCK_EXTERNAL',
			'WP_MAX_MEMORY_LIMIT',
			'WP_MEMORY_LIMIT',
			'WP_SITEURL',
		);
	}
}
