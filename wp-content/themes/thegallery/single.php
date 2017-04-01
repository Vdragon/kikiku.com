<?php get_header(); ?>
<div class="container">
	<div class="container-overlay"></div>
	<?php
		// Check and get Sidebar Class
		$sidebar = get_post_meta($post->ID,'post-option-sidebar-template',true);
		global $default_post_sidebar;
		if( empty( $sidebar ) ){ $sidebar = $default_post_sidebar; }
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}else{
			$sidebar_class = '';
		}

		// Translator words
		if( $gdl_admin_translator == 'enable' ){
			$translator_about_author = get_option(THEME_SHORT_NAME.'_translator_about_author', 'About the Author');
			$translator_social_share = get_option(THEME_SHORT_NAME.'_translator_social_shares', 'Social Share');
		}else{
			$translator_about_author = __('About the Author','gdl_front_end');
			$translator_social_share = __('Social Share','gdl_front_end');
		}
	
	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>">  
		<div class="clear"></div>
		<?php
			// print page title
			echo '<div class="page-header-wrapper">';
			echo '<div class="gdl-page-header-gimmick"></div>';
			echo '<h1 class="gdl-page-title gdl-title">';
			the_title();
			echo '</h1>';
			echo '<div class="gdl-page-caption">';
			echo get_post_meta( $post->ID, 'post-option-caption', true);
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '</div>'; 	// page header wrapper	

			$left_sidebar = get_post_meta( $post->ID , "post-option-choose-left-sidebar", true);
			$right_sidebar = get_post_meta( $post->ID , "post-option-choose-right-sidebar", true);
			global $default_post_left_sidebar, $default_post_right_sidebar;
			if( empty( $left_sidebar )){ $left_sidebar = $default_post_left_sidebar; } 
			if( empty( $right_sidebar )){ $right_sidebar = $default_post_right_sidebar; } 
			
			echo "<div class='gdl-page-float-left'>";
		?>
		
		<div class='gdl-page-item'>
		
		<?php 
			if ( have_posts() ){
				while (have_posts()){
					the_post();

					echo '<div class="sixteen columns mt0">';	
					
					// Single header
					echo '<div class="single-thumbnail-info post-info-color gdl-divider">';
					echo '<div class="single-thumbnail-date">' . get_the_time('M d, Y') . '</div>';
					echo '<div class="single-thumbnail-author"> ' . __('by','gdl_front_end') . ' ' . get_the_author_link() . '</div>';
					the_tags('<div class="single-thumbnail-tag">', ', ', '</div>');
					
					echo '<div class="single-thumbnail-comment">';
					comments_popup_link( __('0','gdl_front_end'),
						__('1','gdl_front_end'),
						__('%','gdl_front_end'), '',
						__('Comments are off','gdl_front_end') );
					echo '</div>';
					echo '<div class="clear"></div>';
					echo '</div>';
					
					// Inside Thumbnail
					if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
						$item_size = "640x200";
					}else if( $sidebar == "both-sidebar" ){
						$item_size = "460x150";
					}else{
						$item_size = "940x300";
					} 
					
					$inside_thumbnail_type = get_post_meta($post->ID, 'post-option-inside-thumbnail-types', true);
					
					switch($inside_thumbnail_type){
					
						case "Image" : 
						
							$thumbnail_id = get_post_meta($post->ID,'post-option-inside-thumbnial-image', true);
							$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_size );
							$thumbnail_full = wp_get_attachment_image_src( $thumbnail_id , 'full' );
							$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
							
							if( !empty($thumbnail) ){
								echo '<div class="blog-thumbnail-image">';
								echo '<a href="' . $thumbnail_full[0] . '" data-rel="prettyPhoto" title="' . get_the_title() . '" ><img src="' . $thumbnail[0] .'" alt="'. $alt_text .'"/></a>'; 
								echo '</div>';
							}
							break;
							
						case "Video" : 
						
							$video_link = get_post_meta($post->ID,'post-option-inside-thumbnail-video', true);
							echo '<div class="blog-thumbnail-video">';
							echo get_video($video_link, gdl_get_width($item_size), gdl_get_height($item_size));
							echo '</div>';							
							break;
							
						case "Slider" : 
						
							$slider_xml = get_post_meta( $post->ID, 'post-option-inside-thumbnail-xml', true); 
							$slider_xml_dom = new DOMDocument();
							$slider_xml_dom->loadXML($slider_xml);
							
							echo '<div class="blog-thumbnail-slider">';
							echo print_flex_slider($slider_xml_dom->documentElement, $item_size);
							echo '</div>';					
							break;
							
					}
					
					echo "<div class='clear'></div>";
					
					echo "<div class='single-content first-letter'>";
					echo the_content();
					echo "</div>";
					
					// About Author
					if(get_post_meta($post->ID, 'post-option-author-info-enabled', true) == "Yes"){
						echo "<div class='about-author-wrapper'>";
						echo "<div class='about-author-avartar'>" . get_avatar( get_the_author_meta('ID'), 90 ) . "</div>";
						echo "<div class='about-author-info'>";
						echo "<div class='about-author-title gdl-link-title gdl-title'>" . $translator_about_author . "</div>";
						echo get_the_author_meta('description');
						echo "</div>";
						echo "<div class='clear'></div>";
						echo "</div>";
					}
					
					// Include Social Shares
					if(get_post_meta($post->ID, 'post-option-social-enabled', true) == "Yes"){
						echo "<div class='social-share-title gdl-link-title gdl-title'>";
						echo $translator_social_share;
						echo "</div>";
						include_social_shares();
						echo "<div class='clear'></div>";
					}
				
					echo '<div class="comment-wrapper">';
					//comments_template();
                    //echo '<div id="cloud-tie-wrapper" class="cloud-tie-wrapper"></div>';
                    ?>
                    <wb:comments url="auto" brandline="y" width="auto" skin="pink" appkey="1955391457" ralateuid="2540693375" ></wb:comments>
                    <!--<script type="text/javascript">
                        (function(){
                            var url = "http://widget.weibo.com/distribution/comments.php?width=0&url=auto&brandline=y&skin=3&ralateuid=2540693375&appkey=1955391457&iframskin=3&dpc=1";
                            url = url.replace("url=auto", "url=" + encodeURIComponent(document.URL));
                            document.write('<iframe id="WBCommentFrame" src="' + url + '" scrolling="no" frameborder="0" style="width:100%"></iframe>');
                        })();
                    </script>
                    <script src="http://tjs.sjs.sinajs.cn/open/widget/js/widget/comment.js" type="text/javascript" charset="utf-8"></script>
                    <script type="text/javascript">
                        window.WBComment.init({
                            "id": "WBCommentFrame"
                        });
                    </script>-->
                    <?php
					echo '</div>';
					
					echo "</div>"; // sixteen-columns
				}
			}
		?>
			
		</div> <!-- gdl-page-item -->
		
		<?php 	
			get_sidebar('left');	
			
			echo "</div>";
			
			get_sidebar('right');
		?>
		
		<div class="clear"></div>
		
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>
