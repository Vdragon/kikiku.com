<?php

	/*
	*	Goodlayers Blog Item File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file contains the function that can print each blog item due to 
	*	different conditions.
	*	---------------------------------------------------------------------
	*/
	
	// size is when no sidebar, side2 is use when 1 sidebar, side 3 is use when 3 sidebar
	if( $gdl_is_responsive ){
		$blog_div_size_num_class = array("class"=>"sixteen columns", "size"=>"940x300", "size2"=>"590x135", "size3"=>"460x150");//从640x220->590x135 20150716

	}else{
		$blog_div_size_num_class = array("class"=>"sixteen columns", "size"=>"940x300", "size2"=>"590x135", "size3"=>"460x150");//从640x220->590x135 20150716
	}	
	
	// Print blog item
	function print_blog_item($item_xml){

		wp_reset_query();
		global $paged;
		global $sidebar;
		global $blog_div_size_num_class;
		
		if(empty($paged)){
			$paged = (get_query_var('page')) ? get_query_var('page') : 1; 
		}
		
		// get the item class and size from array
		$item_type = find_xml_value($item_xml, 'item-size');
		$item_class = $blog_div_size_num_class['class'];
		if( $sidebar == "no-sidebar" ){
			$item_size = $blog_div_size_num_class['size'];
		}else if ( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
			$item_size = $blog_div_size_num_class['size2'];
		}else{
			$item_size = $blog_div_size_num_class['size3'];
		}
				
		// get the blog meta value		
		$header = find_xml_value($item_xml, 'header');
		$num_fetch = find_xml_value($item_xml, 'num-fetch');
		$num_excerpt = find_xml_value($item_xml, 'num-excerpt');
		
		$category = find_xml_value($item_xml, 'category');
		$category = ( $category == 'All' )? '': $category;
		if( !empty($category) ){
			$category_term = get_term_by( 'name', $category , 'category');
			$category = $category_term->slug;
		}

		// print header
		if(!empty($header)){
			echo '<h3 class="blog-header-title title-color mb15 gdl-title">' . $header . '</h3>';
		}
		
		// start fetching database
		query_posts(array('post_type'=>'post', 'paged'=>$paged,
			 'category_name'=>$category, 'posts_per_page'=>$num_fetch  ));		
		
		echo '<div id="blog-item-holder" class="blog-item-holder">';

		print_blog_full($item_class, $item_size, $num_excerpt);
		
		echo '</div>';
		echo '<div class="clear"></div>';
		
		if( find_xml_value($item_xml, "pagination") == "Yes" ){	
			pagination();
		}	
	
	}	
	
	// print the blog thumbnail
	function print_blog_thumbnail( $post_id, $item_size ){
	
		$thumbnail_types = get_post_meta( $post_id, 'post-option-thumbnail-types', true);
		
		if( $thumbnail_types == "Image" || empty($thumbnail_types) ){
		
			$thumbnail_id = get_post_thumbnail_id( $post_id );
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_size );
			$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
			if( !empty($thumbnail) ){
				echo '<div class="blog-thumbnail-image">';
				echo '<a href="' . get_permalink() . '"><img src="' . $thumbnail[0] .'" alt="'. $alt_text .'"/></a></div>';
			}
		
		}else if( $thumbnail_types == "Video" ){
			
			$video_link = get_post_meta( $post_id, 'post-option-thumbnail-video', true); 
			echo '<div class="blog-thumbnail-video">';
			echo get_video($video_link, gdl_get_width($item_size), gdl_get_height($item_size));
			echo '</div>';
		
		}else if ( $thumbnail_types == "Slider" ){

			$slider_xml = get_post_meta( $post_id, 'post-option-thumbnail-xml', true); 
			$slider_xml_dom = new DOMDocument();
			$slider_xml_dom->loadXML($slider_xml);
			
			echo '<div class="blog-thumbnail-slider">';
			echo print_flex_slider($slider_xml_dom->documentElement, $item_size);
			echo '</div>';			
		
		}	
		
	}
	
	// print blog full thumbnail type
	function print_blog_full( $item_class, $item_size, $num_excerpt ){
	
		global $gdl_admin_translator;
		if( $gdl_admin_translator == 'enable' ){
			$translator_continue_reading = get_option(THEME_SHORT_NAME.'_translator_continue_reading', 'Continue Reading →');
		}else{
			$translator_continue_reading = __('Continue Reading →','gdl_front_end');
		}	
		$_i = 1;
		while( have_posts() ){
			the_post();

			echo '<div class="blog-item gdl-divider ' . $item_class . ' mt0">'; 
			
			echo '<h2 class="blog-thumbnail-title post-title-color gdl-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
			echo '<div class="blog-thumbnail-info post-info-color">';
			echo '<div class="blog-thumbnail-date">' . get_the_time('M d, Y') . '</div>';
			echo '<div class="blog-thumbnail-author"> ' . __('by','gdl_front_end') . ' ' . get_the_author_link() . '</div>';	
			the_tags('<div class="single-thumbnail-tag">', ', ', '</div>');
			
			echo '<div class="blog-thumbnail-comment">';
			comments_popup_link( __('0','gdl_front_end'),
				__('1','gdl_front_end'),
				__('%','gdl_front_end'), '',
				__('Comments are off','gdl_front_end') );
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '</div>';

			print_blog_thumbnail( get_the_ID(), $item_size );
			
			echo '<div class="blog-thumbnail-context">';
			echo '<div class="blog-thumbnail-content">' . mb_substr( get_the_excerpt(), 0, $num_excerpt ) . '</div>';	
			echo '<a class="blog-continue-reading" href="' . get_permalink() . '"><em>' . $translator_continue_reading . '</em></a>';
			echo '</div>'; // blog-thumbnail-context
			$is_odd = $_i % 2 != 0 ? true : false;//奇数偶数
			echo '<span class="post_type_label' . ($is_odd ? ' is_odd' : ' is_event') . '"><span class="post_type_label_d">' . get_the_time('d') . '</span><span class="post_type_label_spacer">/</span><span class="post_type_label_m">' . get_the_time('M') . '</span></span>';//显示文章日期标签 20150716
			
			echo '</div>'; // blog-item
			$_i ++;
		}		
		unset($_i);
	}
?>