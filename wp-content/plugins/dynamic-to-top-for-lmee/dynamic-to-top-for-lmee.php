<?php
/*
Plugin Name: Dynamic To Top for lmee
Version: 3.3.1
Plugin URI: http://lmee.net
Description: Adds an automatic and dynamic "To Top" button to scroll long pages back to the top with lmee change.
Author: Matt Varone | lmee change
Author URI: http://lmee.net
*/

/*
|--------------------------------------------------------------------------
| DYNAMIC TO TOP CONSTANTS
|--------------------------------------------------------------------------
*/

define( 'MV_DYNAMIC_TO_TOP_VERSION', '3.3.1' );

/*
|--------------------------------------------------------------------------
| DYNAMIC INITIALIZATION
|--------------------------------------------------------------------------
*/

/** 
 * Plugins Loaded 
 * 
 * Launches on plugins_loaded. Loads internationalization,
 * requires the necessary files. 
 * 
 * @package  Dynamic To Top
 * @since    3.2
 * @return   void
*/

if ( ! function_exists( 'mv_dynamic_to_top_plugins_loaded' ) ) {
    function mv_dynamic_to_top_plugins_loaded() {
        
        // translation
        add_action( 'init', 'mv_dynamic_to_top_load_textdomain' );
        
        // require files
        if ( is_admin() )
            require_once( plugin_dir_path( __FILE__ ) . 'inc/dynamic-to-top-options.php' );
        else
            require_once( plugin_dir_path( __FILE__ ) . 'inc/dynamic-to-top-class.php' );
    }
}
add_action( 'plugins_loaded', 'mv_dynamic_to_top_plugins_loaded' );


/**
 * Load Textdomain
 *
 * @access      private
 * @since       3.3 
 * @return      void
*/

if ( ! function_exists( 'mv_dynamic_to_top_load_textdomain' ) ) {
function mv_dynamic_to_top_load_textdomain() {
        // load textdomain
        load_plugin_textdomain( 'dynamic-to-top', false, dirname( plugin_basename( __FILE__ ) ) . '/lan' );
    }
}

/*
|--------------------------------------------------------------------------
| DYNAMIC TO TOP ACTIVATION
|--------------------------------------------------------------------------
*/

/** 
 * Dynamic To Top Activation
 *
 * @package  Dynamic To Top
 * @since    3.1.5
 * @return   void
*/

if ( ! function_exists( 'mv_dynamic_to_top_activation' ) ) {   
    function mv_dynamic_to_top_activation() {
        
        // check compatibility
        if ( version_compare( get_bloginfo( 'version' ), '3.0' ) >= 0 )
        deactivate_plugins( basename( __FILE__ ) );
        
        // refresh cache
        delete_transient( 'dynamic_to_top_transient_css' );
        
    }
}
register_activation_hook( __FILE__, 'mv_dynamic_to_top_activation' );