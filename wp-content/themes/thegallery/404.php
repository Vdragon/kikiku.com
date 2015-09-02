<?php 
/**
 * 404 ( Not fount page )
 */
get_header();

global $gdl_admin_translator;
if( $gdl_admin_translator == 'enable' ){
	$translator_404_title = get_option(THEME_SHORT_NAME.'_404_title', 'Sorry');
	$translator_404_content = get_option(THEME_SHORT_NAME.'_404_content', 'The page you are looking for doesn\'t seem to exist.');
}else{
	$translator_404_title = __('Sorry','gdl_front_end');		
	$translator_404_content = __('The page you are looking for doesn\'t seem to exist.','gdl_front_end');
}	

?>
<div class="container">
	<div class="container-overlay"></div>
	<div class="content-wrapper">		
		<?php
			// print page title
			echo '<div class="page-header-wrapper">';
			echo '<div class="gdl-page-header-gimmick"></div>';
			echo '<h1 class="gdl-page-title gdl-title">';
			echo $translator_404_title;
			echo '</h1>';
			echo '<div class="clear"></div>';
			echo '</div>'; 	// page header wrapper	
		?>	
		<div class="page-wrapper">
			<div class='gdl-page-item'>
				<div class="sixteen columns mt20">
					<div class="message-box-wrapper red">
						<div class="message-box-title">
							<?php echo $translator_404_title; ?>
						</div>
						<div class="message-box-content">
							<?php echo $translator_404_content; ?>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php get_footer();?>