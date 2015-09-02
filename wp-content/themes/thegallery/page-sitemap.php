<?php 
/**
 * Template Name: Site Map
 */

get_header(); ?>
<div class="container">
	<div class="container-overlay"></div>
	<div class="content-wrapper">
		<?php
			// print page title
			echo '<div class="page-header-wrapper">';
			echo '<div class="gdl-page-header-gimmick"></div>';
			echo '<h1 class="gdl-page-title gdl-title">';
			the_title();
			echo '</h1>';
			echo '<div class="gdl-page-caption">';
			echo get_post_meta( $post->ID, 'page-option-caption', true);
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '</div>'; 	// page header wrapper	
		?>
		<div class="page-wrapper sitemap">	
			<div class='gdl-page-float-left'>
				<div class='gdl-page-item'>
					<div class="one-third column">
						<?php dynamic_sidebar( 'Site Map 1' ); ?>
					</div>
					<div class="one-third column">
						<?php dynamic_sidebar( 'Site Map 2' ); ?>
					</div>
					<div class="one-third column">
						<?php dynamic_sidebar( 'Site Map 3' ); ?>
					</div>
					<div class="clear"></div>
				</div> <!-- gdl-page-item -->
			</div> <!-- gdl-page-float-left -->
			<div class="clear"></div>
		</div> <!-- page-wrapper -->
	</div> <!-- content-wrapper -->
	
<?php get_footer(); ?>