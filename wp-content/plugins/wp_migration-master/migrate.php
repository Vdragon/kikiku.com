<?php
/*
Plugin Name: Managed WordPress Migration
Plugin URI: http://www.starfieldtech.com/
Description: This plugin helps move your site to the Managed WordPress platform
Version: 1.2
Author: Starfield Technologies
Author URI: http://www.starfieldtech.com/
Text Domain: gd-migrate
Domain Path: /languages
*/

/*
Copyright 2013 GoDaddy Operating Company, LLC

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Bail on direct access
defined( 'WPINC' ) or die;

// Grab this dir and file
define( 'GD_MIGRATE_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'GD_MIGRATE_PLUGIN_FILE', __FILE__ );

// Pull in libraries
include( GD_MIGRATE_PLUGIN_DIR . '/lib/wp-stack.php' );

// Spin up a version checker
include( GD_MIGRATE_PLUGIN_DIR . '/classes/version-check.php' );
new GD_Migrate_Version_Check;

// If good to go, start her up
if ( GD_Migrate_Version_Check::$instance->passes() ) {
	// Pull in the plugin
	include( GD_MIGRATE_PLUGIN_DIR . '/classes/plugin.php' );
	include( GD_MIGRATE_PLUGIN_DIR . '/classes/iterators.php' );
	include( GD_MIGRATE_PLUGIN_DIR . '/classes/db-backup.php' );
	include( GD_MIGRATE_PLUGIN_DIR . '/classes/wp-config-alterations.php' );

	// Start the plugin
	new GD_Migrate_Plugin;

	// Register the WP-CLI command
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		include( GD_MIGRATE_PLUGIN_DIR . '/classes/wp-cli.php' );
		WP_CLI::add_command( 'gd-import', 'GD_Migrate_Plugin_Import_Command' );
	}
}
