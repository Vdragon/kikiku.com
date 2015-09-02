<?php get_header(); ?>
<div class="container">
	<div class="container-overlay"></div>
	<?php
		$sidebar = get_option(THEME_SHORT_NAME.'_search_archive_sidebar','no-sidebar');
		$sidebar_class = '';
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}
	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>">
		<div class="page-wrapper archive-wrapper">
			<?php
				// print page title
				echo '<div class="page-header-wrapper">';
				echo '<div class="gdl-page-header-gimmick"></div>';
				echo '<h1 class="gdl-page-title gdl-title">';
				_e('Search', 'gdl_front_end');
				echo '</h1>';
				echo '<div class="gdl-page-caption">';
				the_search_query();
				echo '</div>';
				echo '<div class="clear"></div>';
				echo '</div>'; 	// page header wrapper	

				$left_sidebar = "Search/Archive Left Sidebar";
				$right_sidebar = "Search/Archive Right Sidebar";		
				
				$num_excerpt = get_option(THEME_SHORT_NAME.'_search_archive_num_excerpt', 200);

				global $blog_div_size_num_class;
				$item_class = $blog_div_size_num_class['class'];
				if( $sidebar == "no-sidebar" ){
					$item_size = $blog_div_size_num_class['size'];
				}else if ( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
					$item_size = $blog_div_size_num_class['size2'];
				}else{
					$item_size = $blog_div_size_num_class['size3'];
				}	
					
				echo "<div class='gdl-page-float-left'>";
				echo "<div class='gdl-page-item'>";
				
				if( have_posts() ){

					echo '<div id="blog-item-holder" class="blog-item-holder">';
					print_blog_full($item_class, $item_size, $num_excerpt);
					echo '</div>'; // blog-item-holder
					
					echo '<div class="clear"></div>';
					
					pagination();
				
				}else{
				
					global $gdl_admin_translator;
					if( $gdl_admin_translator == 'enable' ){
						$translator_not_found = get_option(THEME_SHORT_NAME.'_search_not_found', 
							'Sorry, but nothing matched your search criteria. Please try again with some different keywords.');
					}else{
						$translator_not_found = __('Sorry, but nothing matched your search criteria.' . 
							' Please try again with some different keywords.','gdl_front_end');		
					}		
					
					echo '<div class="sixteen columns">';
					echo '<div class="gdl-page-content pb20">';
					echo $translator_not_found;
					echo '</div>';					
					echo '</div>';
				
				}
				
				echo "</div>"; // gdl-page-item
				
				get_sidebar('left');		
				
				echo "</div>"; // gdl-page-float-left
				
				get_sidebar('right');	
			?>
			<div class="clear"></div>
		</div>
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>
