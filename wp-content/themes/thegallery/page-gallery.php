<?php 
/**
 * Template Name: Gallery
 */

get_header(); 
 
?>	
	<div class="supersized-caption-wrapper" id='supersized-caption-wrapper'>
		<div id="slidecaption"></div>
		<div id="slidecaption2"></div>
		<div class="supersized-navigation">
			<div id="prevslide"></div>
			<div id="nextslide"></div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="gdl-gal-container" id="gdl-gal-container">
		<div class="gdl-gal-container-overlay"></div>
		
		<div id="gdl-gal-container-loading-overlay">
			<div id="gdl-gal-container-inner-loading-overlay"></div>
		</div>
		<div id="gdl-gal-container-content"></div>
	</div>
	<?php
		
		$gal_root = '\\' . get_post_meta($post->ID, 'page-option-gallery-root-folder', true);
		if( $gal_root == '\\All' ){ $gal_root = ''; }
		
		
		$width = get_option(THEME_SHORT_NAME.'_first_bottom_slide_width', '210');
		$height = get_option(THEME_SHORT_NAME.'_first_bottom_slide_height', '130');
		$item_size = $width . 'x' . $height;
		
		$directory_list = get_directory_list(GAL_DEST . $gal_root, 'dir');
		echo '<div class="bottom-image-slide1-wrapper" id="gdl-bottom-image-slide">';
		
		echo '<div id="bottom-image-slide-control" class="active"></div>';

		echo '<div class="gdl-bottom-image-slide-inner-wrapper" id="gdl-bottom-image-slide-inner-wrapper">';
		echo '<div class="bottom-image-slide-overlay"></div>';
		echo '<div id="gdl-bottom-image-slide-inner">';
		foreach( $directory_list as $directory ){
		
			$condition = 'name = \'' . mysql_real_escape_string(GAL_PREFIX . $gal_root . '\\' . $directory) . '\'';
			$query_result = galdb_query( $condition );
			
			if( empty($query_result) || $query_result[0]->setting == 'Random Thumbnail' ){
				$random_list = get_directory_list(GAL_DEST . $gal_root . '\\' . $directory, 'image');
				shuffle( $random_list );

				echo '<div class="bottom-image-slide" data-ajax="gdl_get_gallery_data" data-root="' . $gal_root . '" data-dir="' . $directory . '">';
				
				echo '<div class="flexslider flexslider-gallery" style="width:' . $width . 'px; height:' . $height . 'px;">';
				echo '<ul class="slides">';
				for( $i=0; $i<6 && $i<sizeof($random_list) ; $i++ ){
					$source = GAL_DEST . $gal_root . '/' . $directory . '/' . $random_list[$i];
					$img_thumbnail = gdl_resize_gallery_image( $source, GAL_TMB_DEST, $width, $height);
					echo '<li><img src="' . $img_thumbnail . '" alt="" ></li>';
				}				
				echo '</ul>'; 
				echo '</div>'; // flexslider
				
				echo '<div class="bottom-image-slide-title-wrapper" style="width:' . $width . 'px;">';
				echo '<span class="bottom-image-slide-title">';
				if( !empty($query_result[0]->title) ){
					echo $query_result[0]->title;
				}
				echo '</span>';
				echo '</div>'; // bottom-image-slide-title-wrapper
				
				echo '<div class="bottom-image-folder"></div>';				
				
				echo '</div>'; // bottom-image-slide			
			}else{
				$thumbnail_id = $query_result[0]->thumbnail;
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_size );
				$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
				
				if( !empty($thumbnail) ){
					echo '<div class="bottom-image-slide" data-ajax="gdl_get_gallery_data" data-root="' . $gal_root . '" data-dir="' . $directory . '">';
					
					echo '<img src="' . $thumbnail[0] .'" ' . $alt_text . ' style="width:' . $width . 'px; height:' . $height . 'px;" />';
					echo '<div class="bottom-image-slide-title-wrapper" style="width:' . $width . 'px;">';
					echo '<span class="bottom-image-slide-title">';
					if( !empty($query_result[0]->title) ){
						echo $query_result[0]->title;
					}
					echo '</span>';
					echo '</div>'; // bottom-image-slide-title-wrapper
					
					echo '<div class="bottom-image-folder"></div>';				
					
					echo '</div>'; // bottom-image-slide
				}	
			}
			
		}
		echo '<div class="clear"></div>';
		echo '</div>'; // bottom-image-slide-inner
		echo '</div>'; // bottom-image-slide-inner-wrapper
		echo '</div>'; // bottom-image-slide
	
	?>

	<div class="clear"></div>
	</div> <!-- body-wrapper -->

	<?php wp_footer(); ?>	
	<script type="text/javascript"> 	
		<?php include ( TEMPLATEPATH."/javascript/cufon-replace.php" ); ?>
		<?php include ( TEMPLATEPATH."/javascript/supersized.php" ); ?>		
	</script>	
	
	</body>
</html>