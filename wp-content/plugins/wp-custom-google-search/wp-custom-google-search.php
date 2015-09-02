<?php
/**
 * Plugin name: WP Custom Google Search
 * Version: 1.0
 * Plugin URI: http://idelgado.net/plugins/wp-custom-google-search/
 * Author: Iván R. Delgado Martínez 
 * Author URI: http://idelgado.net
 * Description: A customizable google search plugin for posts.
 *
 * Copyright (C) <2012>  <idelgad1@idelgado.net>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Print the google search box into post
 *
 * Call this function anywhere you want in post, usually in single.php:
 *
 * if (function_exists("wpCustomGoogleSearch")) {
 *    wpCustomGoogleSearch();
 * }
 *
 */
if(!function_exists("wpCustomGoogleSearch")) {
function wpCustomGoogleSearch() {
    if(!is_single())
	return false;
    include( plugin_dir_path(__FILE__) . "/CustomGoogleSearchUser.php" );
    CustomGoogleSearchOptions::loadLanguage();
    CustomGoogleSearchUser::renderPostGoogleSearch();
}
}

/**
 * Initialize WP Custom Google Search plugin
 */
if(!function_exists("wpCustomGoogleSearchInit")) {
function wpCustomGoogleSearchInit() {
    require( plugin_dir_path(__FILE__) . "/CustomGoogleSearchOptions.php");
    CustomGoogleSearchOptions::loadLanguage();
    if(is_admin()) {
        include( plugin_dir_path(__FILE__) . "/CustomGoogleSearchAdmin.php");
        $admin = new CustomGoogleSearchAdmin();
        add_action( 'init', array( &$admin, 'init' ) );
    }
}

/**
 * Calling the function that initialize the plugin
 */
wpCustomGoogleSearchInit();
}

?>