jQuery(document).ready(function(){
	jQuery.supersized({
		
		<?php
			$transition_arr = array(
				'Fade' => 1, 'Slide Top' => 2, 'Slide Right' => 3, 
				'Slide Bottom' => 4, 'Slide Left' => 5, 'Carousel Right' => 6, 
				'Carousel Left' => 7
			);
			
			$transition_speed = get_option( THEME_SHORT_NAME.'_supersized_animation_speed' , '700');
			$transition = get_option( THEME_SHORT_NAME.'_supersized_transition' , 'Fade');
			$slide_interval = get_option( THEME_SHORT_NAME.'_supersized_pause_time' , '5000');
		?>
		
		// Functionality
		
		slide_interval          :   <?php echo $slide_interval; ?>,		// Length between transitions
		transition              :   <?php echo $transition_arr[ $transition ]; ?>, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
		transition_speed		:	<?php echo $transition_speed; ?>,		// Speed of transition	
		
		fit_portrait         	:   0,			// Portrait images will not exceed browser height  	

		// Components					
		slide_links				:	0,			// Individual links for each slide (Options: false, 'num', 'name', 'blank')
		thumb_links				:	0,			// Individual thumb links for each slide	
		thumbnail_navigation    :   0,			// Thumbnail navigation
		
		// Components							
		slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
		slides 					:  	[			// Slideshow Images	
										<?php 
											wp_reset_query();
											
											if( is_single() ){
												$bg_fetch_page = get_option(THEME_SHORT_NAME.'_post_background_image');
												$slider_page = get_page_by_title($bg_fetch_page, null, 'page');
												$bg_slider_xml = get_post_meta($slider_page->ID,'page-option-top-slider-xml', true);
											}else if( is_search() || is_archive() ){
												$bg_fetch_page = get_option(THEME_SHORT_NAME.'_archive_background_image');
												$slider_page = get_page_by_title($bg_fetch_page, null, 'page');
												$bg_slider_xml = get_post_meta($slider_page->ID,'page-option-top-slider-xml', true);											
											}else{
												$bg_fetch_page = get_post_meta($post->ID,'page-option-top-slider-types', true);
												if( $bg_fetch_page == 'Current Page'){
													$bg_slider_xml = get_post_meta($post->ID,'page-option-top-slider-xml', true);
												}else{
													$slider_page = get_page_by_title($bg_fetch_page, null, 'page');
													$bg_slider_xml = get_post_meta($slider_page->ID,'page-option-top-slider-xml', true);
												}												
											}												
											$slider_index = false;
											if( !empty($bg_slider_xml) ){
												$slider_xml_dom = new DOMDocument();
												$slider_xml_dom->loadXML($bg_slider_xml);
												foreach( $slider_xml_dom->documentElement->childNodes as $slider_item ){
													$thumbnail_id = find_xml_value($slider_item, 'image');
													$thumbnail = wp_get_attachment_image_src( $thumbnail_id , 'full' );
													
													if( $slider_index ){ echo ","; }
													else{ $slider_index = true; }
													
													echo '{ image: \'' . $thumbnail[0] . '\', title: \'' . find_xml_value($slider_item, 'title') . '\' , caption: \'' . find_xml_value($slider_item, 'caption') . '\' }';
												}
											}

										?>
									]	

	});
});