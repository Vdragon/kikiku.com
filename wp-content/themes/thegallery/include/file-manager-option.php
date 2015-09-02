<?php

	/*	
	*	File manager Option File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*/
	
	// add action to embeded the panel in to dashboard
	add_action('admin_menu','add_file_manager_panel');
	function add_file_manager_panel(){
	
		$page = add_menu_page('GoodLayers File Manager', THEME_FILE_MANAGER, 'administrator', plugin_basename(__FILE__), 'create_file_manager_panel' /*,  GOODLAYERS_PATH.'/include/images/portfolio-icon.png' */);
		
		add_action('admin_print_scripts-' . $page,'register_gdl_file_manager_scripts');
		add_action('admin_print_styles-' . $page,'register_gdl_file_manager_styles');
		
	}	
	
	function register_gdl_file_manager_scripts(){

		wp_deregister_script('gdl-file-manager');
		wp_register_script('gdl-file-manager', GOODLAYERS_PATH.'/include/javascript/gdl-file-manager.js', false, '1.0', true);
		wp_localize_script( 'gdl-file-manager', 'URL', array('goodlayers' => GOODLAYERS_PATH, 'ajaxurl'=>AJAX_URL ));		
		wp_enqueue_script('gdl-file-manager');			
		
		wp_deregister_script('jquery-ui');
		wp_register_script('jquery-ui', GOODLAYERS_PATH.'/include/plugin/elfinder/js/jquery-ui-1.8.13.custom.min.js', false, '1.0', true);
		wp_enqueue_script('jquery-ui');	
		
		wp_deregister_script('elfinder');
		wp_register_script('elfinder', GOODLAYERS_PATH.'/include/plugin/elfinder/js/elfinder.full.js', false, '1.0', true);
		wp_enqueue_script('elfinder');		

		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');		
			
	}
	function register_gdl_file_manager_styles(){
		wp_enqueue_style('gdl-file-manager',GOODLAYERS_PATH.'/include/stylesheet/gdl-file-manager.css');
		wp_enqueue_style('elfinder',GOODLAYERS_PATH.'/include/plugin/elfinder/css/elfinder.css');
		wp_enqueue_style('jquery_ui',GOODLAYERS_PATH.'/include/plugin/elfinder/css/smoothness/jquery-ui-1.8.13.custom.css');
		
		wp_enqueue_style('thickbox');
	}	
	
	
	// start creating the goodlayers panel ( by calling function to create menu and elements )
	function create_file_manager_panel(){
		echo '<div id="file-manager-panel-wrapper">';
		echo '<div id="file-manager-panel">';

		echo '</div>'; //file-manager-panel
		echo '</div>'; //file-manager-panel-wrapper
		
	}	
?>