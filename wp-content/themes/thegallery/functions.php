<?php 

	/*	
	*	Goodlayers Function File
	*	---------------------------------------------------------------------
	*	This file include all of important function and features of the theme
	*	to make it available for later use.
	*	---------------------------------------------------------------------
	*/

	// constants
	define('THEME_SHORT_NAME','thg'); 
	define('THEME_FULL_NAME','The Gallery');
	define('THEME_FILE_MANAGER','File Manager');
	define('GOODLAYERS_PATH', get_template_directory_uri());
	define('AJAX_URL', admin_url( 'admin-ajax.php' ));
	define('FONT_SAMPLE_TEXT', 'Sample Font'); // sample font text of the goodlayers backoffice panel
	
	define('GAL_PREFIX', 'Gallery');
	define('GAL_DEST', TEMPLATEPATH . '/gallery'); // gallery thumbnail destination
	define('GAL_TMB_DEST', TEMPLATEPATH . '/gallery/A-gdl-gallery-resize'); // gallery thumbnail destination
	define('GAL_REAL_DEST', GOODLAYERS_PATH . '/gallery'); // gallery thumbnail destination
	define('GAL_IMG_DEST', GOODLAYERS_PATH . '/gallery/A-gdl-gallery-resize'); // gallery thumbnail destination
	
	$gdl_icon_type = get_option(THEME_SHORT_NAME.'_icon_type','light');
	$gdl_footer_icon_type = get_option(THEME_SHORT_NAME.'_footer_icon_type','light');

	$gdl_admin_translator = get_option(THEME_SHORT_NAME.'_enable_admin_translator','enable');
	$gdl_is_responsive = false;
	
	$default_post_sidebar = get_option(THEME_SHORT_NAME.'_default_post_sidebar','post-no-sidebar');
	$default_post_sidebar = str_replace('post-', '', $default_post_sidebar);
	$default_post_left_sidebar = get_option(THEME_SHORT_NAME.'_default_post_left_sidebar','');
	$default_post_right_sidebar = get_option(THEME_SHORT_NAME.'_default_post_right_sidebar','');	
	
	include_once('include/include-script.php'); // include all javascript and style in to the theme
	include_once('include/plugin/utility.php'); // utility function
	include_once('include/function-regist.php'); // registered wordpress function
	include_once('include/goodlayers-option.php'); // goodlayers panel
	include_once('include/file-manager-option.php'); // include the file manager option in to the backoffice
	include_once('include/plugin/gallery-db.php'); // create and manipulate the gdldb table
	include_once('include/plugin/fontloader.php'); // load necessary font
	include_once('include/plugin/shortcode-generator.php'); // shortcode
	
	// dashboard option
	include_once('include/meta-template.php'); // template for post portfolio and gallery
	include_once('include/post-option.php');	// meta of post post_type
	include_once('include/page-option.php'); // meta of page post_type
	include_once('include/portfolio-option.php'); // meta of portfolio post_type
	include_once('include/testimonial-option.php'); // meta of portfolio post_type
	include_once('include/gallery-option.php'); // meta of portfolio post_type
	
	// exterior plugins
	include_once('include/plugin/really-simple-captcha/really-simple-captcha.php'); // capcha comment plugin class
	include_once('include/plugin/filosofo-image/filosofo-custom-image-sizes.php'); // Custom image size plugin
	include_once('include/plugin/dropdown-menus.php'); // Custom dropdown menu
	
	if(!is_admin()){
		include_once('include/plugin/misc.php');	 // misc function to use at font-end
		include_once('include/plugin/page-item.php');	 // organize page item element
		include_once('include/plugin/blog-item.php');	 // organize blog item element
		include_once('include/plugin/comment.php'); // function to get list of comment
		include_once('include/plugin/pagination/pagination.php'); // page divider plugin
		include_once('include/plugin/social-shares.php'); // page divider plugin
		
		include_once('include/plugin/really-simple-captcha/cbnet-really-simple-captcha-comments.php'); // capcha comment plugin
	}
	
	// include custom widget
	include_once('include/plugin/custom-widget/custom-blog-widget.php'); 
	include_once('include/plugin/custom-widget/custom-port-widget.php'); 
	include_once('include/plugin/custom-widget/custom-port-widget-2.php'); 
	include_once('include/plugin/custom-widget/popular-post-widget.php'); 
	include_once('include/plugin/custom-widget/contact-widget.php'); 
	include_once('include/plugin/custom-widget/flickr-widget.php'); 
	include_once('include/plugin/custom-widget/twitter-widget.php');
	
	// Add Another theme support
	if ( ! isset( $content_width ) ) $content_width = 980;
	
	add_theme_support( 'automatic-feed-links' );

	// remove admin bar
	// add_filter('show_admin_bar', '__return_false');
	
?>