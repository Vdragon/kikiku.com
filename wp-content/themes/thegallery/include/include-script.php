<?php

	/*	
	*	Goodlayers Include Script File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file manage to embed the stylesheet and javascript to each page
	*	based on the content of that page.
	*	---------------------------------------------------------------------
	*/
	
	add_action('init', 'register_all_gdl_scripts');
	function register_all_gdl_scripts(){
	
		wp_deregister_script('jquery');
		wp_register_script('jquery', GOODLAYERS_PATH.'/include/javascript/jquery.min.js', false, '1.7.1', false);
		wp_enqueue_script('jquery');
		
		if( $GLOBALS['pagenow'] != 'wp-login.php' ){
			if(is_admin()){
			
				wp_enqueue_style('gdl-back-office', GOODLAYERS_PATH.'/include/stylesheet/gdl-back-office.css');
				add_action('add_meta_boxes', 'register_meta_script');
				
			}else{
				
				wp_enqueue_style('style-custom', GOODLAYERS_PATH.'/style-custom.php');
				add_action('wp_print_styles','register_non_admin_styles');
				add_action('wp_print_scripts','register_non_admin_scripts');
				
			}
			//加载tooltip
			wp_deregister_script('kikiku');
			wp_register_script('kikiku', GOODLAYERS_PATH.'/javascript/kikiku.js', array('jquery'), '1.0', true);
			wp_enqueue_script('kikiku');
		}
		
	}
	/* 	---------------------------------------------------------------------
	*	This section include the back-end script
	*	---------------------------------------------------------------------
	*/ 
	
	function register_meta_script(){
		global $post_type;
		
		wp_enqueue_style('ie-style',GOODLAYERS_PATH . '/stylesheet/ie-style.php?path=' . GOODLAYERS_PATH);		
		
		// register style and script when access to the "page" post_type page
		if( $post_type == 'page' ){
		
			wp_enqueue_style('meta-css',GOODLAYERS_PATH.'/include/stylesheet/meta-css.css');
			wp_enqueue_style('page-dragging',GOODLAYERS_PATH.'/include/stylesheet/page-dragging.css');
			wp_enqueue_style('image-picker',GOODLAYERS_PATH.'/include/stylesheet/image-picker.css');
			wp_enqueue_style('confirm-dialog',GOODLAYERS_PATH.'/include/stylesheet/jquery.confirm.css');

			wp_deregister_script('image-picker');
			wp_register_script('image-picker', GOODLAYERS_PATH.'/include/javascript/image-picker.js', false, '1.0', true);
			wp_enqueue_script('image-picker');
		
			wp_deregister_script('page-dragging');
			wp_register_script('page-dragging', GOODLAYERS_PATH.'/include/javascript/page-dragging.js', false, '1.0', true);
			wp_enqueue_script('page-dragging');
			
			wp_deregister_script('edit-box');
			wp_register_script('edit-box', GOODLAYERS_PATH.'/include/javascript/edit-box.js', false, '1.0', true);
			wp_enqueue_script('edit-box');

			wp_deregister_script('confirm-dialog');
			wp_register_script('confirm-dialog', GOODLAYERS_PATH.'/include/javascript/jquery.confirm.js', false, '1.0', true);
			wp_enqueue_script('confirm-dialog');
			
		// register style and script when access to the "post" post_type page
		}else if( $post_type == 'post' || $post_type == 'portfolio' || $post_type == 'gallery'){
		
			wp_enqueue_style('meta-css',GOODLAYERS_PATH.'/include/stylesheet/meta-css.css');
			wp_enqueue_style('image-picker',GOODLAYERS_PATH.'/include/stylesheet/image-picker.css');
			wp_enqueue_style('confirm-dialog',GOODLAYERS_PATH.'/include/stylesheet/jquery.confirm.css');
			
			wp_deregister_script('post-effects');
			wp_register_script('post-effects', GOODLAYERS_PATH.'/include/javascript/post-effects.js', false, '1.0', true);
			wp_enqueue_script('post-effects');
			
			wp_deregister_script('image-picker');
			wp_register_script('image-picker', GOODLAYERS_PATH.'/include/javascript/image-picker.js', false, '1.0', true);
			wp_localize_script( 'image-picker', 'URL', array('goodlayers' => GOODLAYERS_PATH ));
			wp_enqueue_script('image-picker');
			
			wp_deregister_script('confirm-dialog');
			wp_register_script('confirm-dialog', GOODLAYERS_PATH.'/include/javascript/jquery.confirm.js', false, '1.0', true);
			wp_enqueue_script('confirm-dialog');
		
		// register style and script when access to the "testimonial" post_type page		
		}else if( $post_type == 'testimonial' ){
		
			wp_enqueue_style('meta-css',GOODLAYERS_PATH.'/include/stylesheet/meta-css.css');
		
		}
		
	}
	
	// register script in goodlayers panel
	function register_goodlayers_panel_scripts(){

		wp_enqueue_style('ie-style',GOODLAYERS_PATH . '/stylesheet/ie-style.php?path=' . GOODLAYERS_PATH);	
	
		wp_deregister_script('jquery-ui');
		wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js', false, '1.0', false);
		wp_enqueue_script('jquery-ui');
	
		wp_deregister_script('cufon');
		wp_register_script('cufon', GOODLAYERS_PATH.'/javascript/cufon.js', false, '1.0', false);
		wp_enqueue_script('cufon');
	
		wp_deregister_script('gdl-panel');
		wp_register_script('gdl-panel', GOODLAYERS_PATH.'/include/javascript/gdl-panel.js', false, '1.0', true);
		wp_localize_script( 'gdl-panel', 'URL', array('goodlayers' => GOODLAYERS_PATH, 'sample_text' => FONT_SAMPLE_TEXT ));
		wp_enqueue_script('gdl-panel');
		
		wp_deregister_script('mini-color');
		wp_register_script('mini-color', GOODLAYERS_PATH.'/include/javascript/jquery.miniColors.js', false, '1.0', true);
		wp_enqueue_script('mini-color');
		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		
		wp_deregister_script('confirm-dialog');
		wp_register_script('confirm-dialog', GOODLAYERS_PATH.'/include/javascript/jquery.confirm.js', false, '1.0', true);
		wp_enqueue_script('confirm-dialog');
		
	}
	
	// register style in goodlayers panel
	function register_goodlayers_panel_styles(){
	
		wp_enqueue_style('jquery-ui',GOODLAYERS_PATH.'/include/stylesheet/jquery-ui-1.8.16.custom.css');
		wp_enqueue_style('gdl-panel',GOODLAYERS_PATH.'/include/stylesheet/gdl-panel.css');
		wp_enqueue_style('mini-color',GOODLAYERS_PATH.'/include/stylesheet/jquery.miniColors.css');
		wp_enqueue_style('thickbox');
		wp_enqueue_style('confirm-dialog',GOODLAYERS_PATH.'/include/stylesheet/jquery.confirm.css');
	}
	
	/* 	---------------------------------------------------------------------
	*	this section include the front-end script
	*	---------------------------------------------------------------------
	*/ 
	
	// Register all stylesheet
	function register_non_admin_styles(){
	
		global $post;
		
		// Navigation Menu
		wp_enqueue_style('superfish',GOODLAYERS_PATH.'/stylesheet/superfish.css');
		
		if(is_single()){
			wp_enqueue_style('fancyBox',GOODLAYERS_PATH.'/fancyBox/source/jquery.fancybox.css');
		}else{
			wp_enqueue_style('prettyPhoto',GOODLAYERS_PATH.'/stylesheet/prettyPhoto.css');
		}
		
		// the background slider (supersized)
		wp_enqueue_style('supersized',GOODLAYERS_PATH.'/stylesheet/supersized.css');
		wp_enqueue_style('supersized-shutter',GOODLAYERS_PATH.'/stylesheet/supersized.shutter.css');
		
		if( is_search() || is_archive() ){
		
			wp_enqueue_style('flex-slider',GOODLAYERS_PATH.'/stylesheet/flexslider.css');
			
		// Post post_type
		}else if( isset($post) && $post->post_type == 'post' || 
			isset($post) && $post->post_type == 'portfolio' ){
		
			// If using slider (flex slider)	
			global $gdl_post_thumbnail;
			$gdl_post_thumbnail = get_post_meta($post->ID,'post-option-inside-thumbnail-types', true);
			
			if( $gdl_post_thumbnail == 'Slider'){
			
				wp_enqueue_style('flex-slider',GOODLAYERS_PATH.'/stylesheet/flexslider.css');
				
			}
			
		// Page post_type
		}else if( isset($post) && $post->post_type == 'page' ){
		
			global $gdl_page_xml, $gdl_top_slider_type, $gdl_top_slider_xml;
			$gdl_page_xml = get_post_meta($post->ID,'page-option-item-xml', true);
			
			$gdl_top_slider_type = get_post_meta($post->ID,'page-option-top-slider-types', true);
			
			$gdl_top_slider_xml = get_post_meta($post->ID,'page-option-top-slider-xml', true);
			
			// If using nivo slider
			if( strpos($gdl_page_xml,'<slider-type>Nivo Slider</slider-type>') > -1 ||
				$gdl_top_slider_type == 'Nivo Slider' ){
			
				wp_enqueue_style('nivo-slider',GOODLAYERS_PATH.'/stylesheet/nivo-slider.css');
				wp_enqueue_style('nivo-slider-style',GOODLAYERS_PATH.'/stylesheet/nivo-slider-style.css');
				
			}				
						
			// If using flex slider
			if(	strpos($gdl_page_xml,'<slider-type>Flex Slider</slider-type>') > -1 || 
				strpos($gdl_page_xml, '<Portfolio>') > -1 ||
				strpos($gdl_page_xml, '<Blog>') > -1 ||
				is_page_template ('page-gallery.php') ||
				$gdl_top_slider_type == 'Flex Slider'){
				
				wp_enqueue_style('flex-slider',GOODLAYERS_PATH.'/stylesheet/flexslider.css');
				
			}
			
			// If using anything slider
			if( strpos($gdl_page_xml,'<slider-type>Anything Slider</slider-type>') > -1  ||
				$gdl_top_slider_type == 'Anything Slider' ){
				
				wp_enqueue_style('anythingSlider',GOODLAYERS_PATH.'/stylesheet/anythingslider.css');
		
			}
			
		}

	}
	
	// Register all scripts
	function register_non_admin_scripts(){
	
		global $post;
		global $gdl_is_responsive;
		global $goodlayers_element;		

		// Navigation Menu
		wp_deregister_script('superfish');
		wp_register_script('superfish', GOODLAYERS_PATH.'/javascript/superfish.js', false, '1.0', true);
		wp_enqueue_script('superfish');	

		wp_deregister_script('supersub');
		wp_register_script('supersub', GOODLAYERS_PATH.'/javascript/supersub.js', false, '1.0', true);
		wp_enqueue_script('supersub');			
		
		wp_deregister_script('hover-intent');
		wp_register_script('hover-intent', GOODLAYERS_PATH.'/javascript/hoverIntent.js', false, '1.0', true);
		wp_enqueue_script('hover-intent');			
		
		wp_deregister_script('gdl-scripts');
		wp_register_script('gdl-scripts', GOODLAYERS_PATH.'/javascript/gdl-scripts.js', false, '1.0', true);
		wp_enqueue_script('gdl-scripts');
		
		wp_deregister_script('easing');
		wp_register_script('easing', GOODLAYERS_PATH.'/javascript/jquery.easing.js', false, '1.0', true);
		wp_enqueue_script('easing');
		
		if(is_single()){
			wp_deregister_script('fancyBox');
			wp_register_script('fancyBox', GOODLAYERS_PATH.'/fancyBox/source/jquery.fancybox.pack.js', false, '2.1.5', true);
			wp_enqueue_script('fancyBox');
		}else{
			wp_deregister_script('prettyPhoto');
			wp_register_script('prettyPhoto', GOODLAYERS_PATH.'/javascript/jquery.prettyPhoto.js', false, '1.0', true);
			wp_enqueue_script('prettyPhoto');
		}
		
		// the background slider (supersized)
		wp_deregister_script('supersized');
		wp_register_script('supersized', GOODLAYERS_PATH.'/javascript/supersized.3.2.7.min.js', false, '1.0', true);
		wp_enqueue_script('supersized');		

		wp_deregister_script('supersized-shutter');
		wp_register_script('supersized-shutter', GOODLAYERS_PATH.'/javascript/supersized.shutter.js', false, '1.0', true);
		wp_enqueue_script('supersized-shutter');		
		
		// if choosing the responsive option
		if( $gdl_is_responsive ){
			wp_deregister_script('fitvids');
			wp_register_script('fitvids', GOODLAYERS_PATH.'/javascript/jquery.fitvids.js', false, '1.0', false);
			wp_enqueue_script('fitvids');		
		}else{
			wp_deregister_script('preloader');
			wp_register_script('preloader', GOODLAYERS_PATH.'/javascript/jquery.preloader.js', false, '1.0', false);
			wp_localize_script( 'preloader', 'URL', array('goodlayers' => GOODLAYERS_PATH) );
			wp_enqueue_script('preloader');
		}
		
		// Search and archive page
		if( is_search() || is_archive() ){

			$flex_setting = get_gdl_slider_option_array($goodlayers_element['gdl_panel_flex_slider']);
			$flex_setting = array_merge($flex_setting, array('controlsContainer'=>'.flexslider'));
		
			wp_deregister_script('flex-slider');
			wp_register_script('flex-slider', GOODLAYERS_PATH.'/javascript/jquery.flexslider.js', false, '1.0', true);
			wp_localize_script( 'flex-slider', 'FLEX', $flex_setting);
			wp_enqueue_script('flex-slider');	
		
		// Post post_type
		}else if( isset($post) &&  $post->post_type == 'post' || 
			isset($post) &&  $post->post_type == 'portfolio'  ){
		
			// If using slider (flex slider)	
			global $gdl_post_thumbnail;
			
			if( $gdl_post_thumbnail == 'Slider'){
			
				$flex_setting = get_gdl_slider_option_array($goodlayers_element['gdl_panel_flex_slider']);
				$flex_setting = array_merge($flex_setting, array('controlsContainer'=>'.slider-wrapper'));
			
				wp_deregister_script('flex-slider');
				wp_register_script('flex-slider', GOODLAYERS_PATH.'/javascript/jquery.flexslider.js', false, '1.0', true);
				wp_localize_script( 'flex-slider', 'FLEX', $flex_setting);
				wp_enqueue_script('flex-slider');	
				
			}
		
		// Page post_type
		}else if( isset($post) &&  $post->post_type == 'page' ){
			
			global $gdl_page_xml, $gdl_top_slider_type, $gdl_top_slider_xml;
			
			//  If using jcarousellite
			if( strpos($gdl_page_xml,'<display-type>Testimonial Category</display-type>') > -1 ){
				wp_deregister_script('jcarousellite');
				wp_register_script('jcarousellite', GOODLAYERS_PATH.'/javascript/jquery.jcarousellite.js', false, '1.0', true);
				wp_enqueue_script('jcarousellite');
			}
			
			// If using nivo slider
			if( strpos($gdl_page_xml,'<slider-type>Nivo Slider</slider-type>') > -1 ||
				$gdl_top_slider_type == 'Nivo Slider' ){
			
				$nivo_setting = get_gdl_slider_option_array($goodlayers_element['gdl_panel_nivo_slider']);
				
				wp_deregister_script('nivo-slider');
				wp_register_script('nivo-slider', GOODLAYERS_PATH.'/javascript/jquery.nivo.slider.pack.js', false, '1.0', true);
				wp_localize_script( 'nivo-slider', 'NIVO', $nivo_setting);
				wp_enqueue_script('nivo-slider');
				
			}
			
			// If using flex slider
			if( strpos($gdl_page_xml, '<slider-type>Flex Slider</slider-type>') > -1 ||
				strpos($gdl_page_xml, '<Portfolio>') > -1 ||
				strpos($gdl_page_xml, '<Blog>') > -1 ||
				$gdl_top_slider_type == 'Flex Slider'){
			
				$flex_setting = get_gdl_slider_option_array($goodlayers_element['gdl_panel_flex_slider']);
				$flex_setting = array_merge($flex_setting, array('controlsContainer'=>'.flexslider'));
			
				wp_deregister_script('flex-slider');
				wp_register_script('flex-slider', GOODLAYERS_PATH.'/javascript/jquery.flexslider.js', false, '1.0', true);
				wp_localize_script( 'flex-slider', 'FLEX', $flex_setting);
				wp_enqueue_script('flex-slider');	
					
			}
			
			// If using anything slider
			if( strpos($gdl_page_xml,'<slider-type>Anything Slider</slider-type>') > -1 ||
				$gdl_top_slider_type == 'Anything Slider' ){
				
				$anything_setting = get_gdl_slider_option_array($goodlayers_element['gdl_panel_anything_slider']);
				
				wp_deregister_script('anythingSlider');
				wp_register_script('anythingSlider', GOODLAYERS_PATH.'/javascript/jquery.anythingslider.js', false, '1.0', true);
				wp_localize_script( 'anythingSlider', 'ANYTHING', $anything_setting);
				wp_enqueue_script('anythingSlider');
				
				// If using video in anything slider
				if( strpos($gdl_page_xml,'<linktype>Link to Video</linktype>') > -1 ||
					strpos($gdl_top_slider_xml,'<linktype>Link to Video</linktype>') > -1 ){
				
					wp_deregister_script('anything-swfobject');
					wp_register_script('anything-swfobject', GOODLAYERS_PATH.'/javascript/anything-swfobject.js', false, '1.0', true);
					wp_enqueue_script('anything-swfobject');	
					
					wp_deregister_script('anythingSlider-video');
					wp_register_script('anythingSlider-video', GOODLAYERS_PATH.'/javascript/jquery.anythingslider.video.js', false, '1.0', true);
					wp_enqueue_script('anythingSlider-video');
									
				}
				
			}
			
			// If using filterable plugin
			if( strpos($gdl_page_xml,'<filterable>Yes</filterable>') > -1 ){
			
				wp_deregister_script('filterable');
				wp_register_script('filterable', GOODLAYERS_PATH.'/javascript/jquery.filterable.js', false, '1.0', true);
				wp_enqueue_script('filterable');				
			
			}
			
			// If use contact-form
			if( strpos($gdl_page_xml,'<Contact-Form>') > -1 ){
			
				wp_deregister_script('contact-form');
				wp_register_script('contact-form', GOODLAYERS_PATH.'/javascript/gdl-contactform.js', false, '1.0', true);
				wp_localize_script( 'contact-form', 'MyAjax', array( 'ajaxurl' => AJAX_URL ) );
				wp_enqueue_script('contact-form');				
						
			}
			
			if( is_page_template ('page-gallery.php') ){
				wp_deregister_script('gdl-gal-script');
				wp_register_script('gdl-gal-script', GOODLAYERS_PATH.'/javascript/gdl-gal-script.js', false, '1.0', true);
				wp_localize_script( 'gdl-gal-script', 'URL', array('goodlayers' => GOODLAYERS_PATH, 'ajaxurl'=>AJAX_URL ));		
				wp_enqueue_script('gdl-gal-script');	
				
				wp_deregister_script('filterable');
				wp_register_script('filterable', GOODLAYERS_PATH.'/javascript/jquery.filterable.js', false, '1.0', true);
				wp_enqueue_script('filterable');				

				wp_deregister_script('jplayer');
				wp_register_script('jplayer', GOODLAYERS_PATH.'/javascript/jplayer/jquery.jplayer.min.js', false, '1.0', true);
				wp_enqueue_script('jplayer');		

				$flex_setting = get_gdl_slider_option_array($goodlayers_element['gdl_panel_flex_slider']);
				$flex_setting = array_merge($flex_setting, array('controlsContainer'=>'.flexslider'));
				
				wp_deregister_script('flex-slider');
				wp_register_script('flex-slider', GOODLAYERS_PATH.'/javascript/jquery.flexslider.js', false, '1.0', true);
				wp_localize_script('flex-slider', 'FLEX', $flex_setting);
				wp_enqueue_script('flex-slider');					
			}
			
		}
	
		// Comment Script
		if(is_singular() && comments_open() && get_option('thread_comments')){
		
			wp_enqueue_script( 'comment-reply' ); 
			
		}
		

		
	}
?>