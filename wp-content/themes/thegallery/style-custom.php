<?php
	/*	
	*	Goodlayers Custom Style File (style-custom.php)
	*	---------------------------------------------------------------------
	*	This file fetch all style options in admin panel to generate the css
	*	to attach to header.php file
	*	---------------------------------------------------------------------
	*/

	header("Content-type: text/css;");
	
	$current_url = dirname(__FILE__);
	$wp_content_pos = strpos($current_url, 'wp-content');
	$wp_content = substr($current_url, 0, $wp_content_pos);

	require_once($wp_content . 'wp-load.php');
	
?>
   
/* Logo
   ================================= */
.logo-wrapper{ 
	margin-top: <?php echo get_option(THEME_SHORT_NAME . "_logo_top_margin", '15'); ?>px;
	margin-left: <?php echo get_option(THEME_SHORT_NAME . "_logo_left_margin", '15'); ?>px;
	margin-bottom: <?php echo get_option(THEME_SHORT_NAME . "_logo_bottom_margin", '15'); ?>px;
}  
  
/* Social Network
   ================================= */
.social-wrapper{
	margin-top: <?php echo get_option(THEME_SHORT_NAME . "_social_wrapper_margin", '33'); ?>px;
}  
   
/* Font Size
   ================================= */
h1{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h1_size", '30'); ?>px;
}
h2{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h2_size", '25'); ?>px;
}
h3{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h3_size", '20'); ?>px;
}
h4{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h4_size", '18'); ?>px;
}
h5{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h5_size", '16'); ?>px;
}
h6{
	font-size: <?php echo get_option(THEME_SHORT_NAME . "_h6_size", '15'); ?>px;
}

/* Element Color
   ================================= */
   
html{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_body_background", '#222222'); ?>;
}
div.container-overlay{
	background: <?php echo get_option(THEME_SHORT_NAME . "_container_background", '#000000'); ?>;
	<?php
		$container_opacity = get_option(THEME_SHORT_NAME . "_container_opacity", '0.7');
		$container_opacity_ie =  ((float) $container_opacity) * 100;
	?>
	opacity: <?php echo $container_opacity; ?>; 
	filter: alpha(opacity=<?php echo $container_opacity_ie; ?>);
}
div.divider{
	border-bottom: 1px solid <?php echo get_option(THEME_SHORT_NAME . "_divider_line", '#ececec'); ?>;
}


/* Font Family 
  ================================= */
/*body{
	font-family: <?php echo substr(get_option(THEME_SHORT_NAME . "_content_font"), 2, -2); ?>;
}*/
/*h1, h2, h3, h4, h5, h6, .gdl-title{
	font-family: <?php echo substr(get_option(THEME_SHORT_NAME . "_header_font"), 2, -2); ?>;
}*/
.stunning-text-wrapper{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_stunning_text_background_color", '#ffffff'); ?> !important;
}
/*#slidecaption{
	font-family: <?php echo substr(get_option(THEME_SHORT_NAME . "_bg_slider_title_font"), 2, -2); ?>;
}*/
.stunning-text-caption{
	color: <?php echo get_option(THEME_SHORT_NAME . "_stunning_text_caption_color", '#666666'); ?>;
}
.supersized-caption-wrapper{
	color: <?php echo get_option(THEME_SHORT_NAME . "_bg_slider_text_color", '#ffffff'); ?>;
}
  
/* Font Color
   ================================= */
body{
	color: <?php echo get_option(THEME_SHORT_NAME . "_content_color", '#f7f7f7'); ?> !important;
}
a{
	color: <?php echo get_option(THEME_SHORT_NAME . "_link_color", '#ffbc2d'); ?>;
}
.footer-wrapper a{
	color: <?php echo get_option(THEME_SHORT_NAME . "_footer_link_color", '#ffbc2d'); ?>;
}
.gdl-link-title{
	color: <?php echo get_option(THEME_SHORT_NAME . "_link_color", '#ffbc2d'); ?> !important;
}
a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME . "_link_hover_color", '#ffbc2d'); ?>;
}
.footer-wrapper a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME . "_footer_link_hover_color", '#ffd885'); ?>;
}
.gdl-slider-title{
	color: <?php echo get_option(THEME_SHORT_NAME . "_slider_title_color", '#ffbc2d'); ?> !important;
}  
.gdl-slider-caption, .nivo-caption{
	color: <?php echo get_option(THEME_SHORT_NAME . "_slider_caption_color", '#ffffff'); ?> !important;
}  
div.slider-bottom-gimmick{ 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_full_slider_bottom_line', '#ebebeb'); ?>; 
}
h1, h2, h3, h4, h5, h6, .title-color{
	color: <?php echo get_option(THEME_SHORT_NAME.'_title_color', '#494949'); ?>;
}
.sidebar-title-color{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sidebar_title_color', '#ffbc2d'); ?> !important;
}
.right-sidebar-wrapper a,
.left-sidebar-wrapper a{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sidebar_link_color', '#ffffff'); ?>;
}
.right-sidebar-wrapper a:hover,
.left-sidebar-wrapper a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sidebar_link_hover_color', '#ffbc2d'); ?>;
}
div.shortcode-block-quote-left, div.shortcode-block-quote-right, div.shortcode-block-quote-center{
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_block_quote_border', '#212121'); ?> !important;
}
.block-quote, .block-quote p{
	color: <?php echo get_option(THEME_SHORT_NAME.'_block_quote_border', '#777777'); ?> !important;
}

/* Page 
   ================================= */
div.page-header-wrapper{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_page_header_background_color', '#333333'); ?>;
	border-bottom: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_page_header_bottom_border', '#000000'); ?>;
}   
div.page-header-wrapper h1{
	color: <?php echo get_option(THEME_SHORT_NAME.'_page_header_text_color', '#ffffff'); ?>;
}
div.gdl-page-caption{
	color: <?php echo get_option(THEME_SHORT_NAME.'_page_header_caption_color', '#acacac'); ?>;
}
div.gdl-page-header-gimmick{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_page_header_left_line', '#ffbc2d'); ?>;
}

/* Gallery
   ================================= */
div.bottom-image-slide1-wrapper .bottom-image-slide{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_main_bottom_slide_text', '#151515'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_main_bottom_slide_bg', '#ffffff'); ?>;
}
div.bottom-image-slide1-wrapper .bottom-image-slide-overlay{ 
   background-color: <?php echo get_option(THEME_SHORT_NAME.'_main_bottom_slide_overlay', '#000000'); ?>;
	<?php
		$overlay_opacity = get_option(THEME_SHORT_NAME . "_main_bottom_slide_overlay_opacity", '0.55');
		$overlay_opacity_ie =  ((float) $overlay_opacity) * 100;
	?>
	opacity: <?php echo $overlay_opacity; ?>; 
	filter: alpha(opacity=<?php echo $overlay_opacity_ie; ?>);   
}

div.gdl-gal-container-header-wrapper{ 
   background-color: <?php echo get_option(THEME_SHORT_NAME.'_gallery_header_background', '#000000'); ?>;
	<?php
		$overlay_opacity = get_option(THEME_SHORT_NAME . "_gallery_header_background_opacity", '0.85');
		$overlay_opacity_ie =  ((float) $overlay_opacity) * 100;
	?>
	opacity: <?php echo $overlay_opacity; ?>; 
	filter: alpha(opacity=<?php echo $overlay_opacity_ie; ?>); 
}
div.gdl-gal-container-header-gimmick{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_gallery_header_left_line', '#ffbc2d'); ?>;
}
h1.gdl-gal-container-title{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_gallery_header_title', '#ffffff'); ?>;
}
div.gdl-gal-container-caption{
	color: <?php echo get_option(THEME_SHORT_NAME.'_gallery_header_caption', '#acacac'); ?>;
}
.jp-audio .jp-play-bar{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_gallery_audio_progress_bar', '#ffbf38'); ?>;
}
div.bottom-image-slide2-wrapper .bottom-image-slide img,
div.bottom-image-slide2-wrapper .flexslider-gallery{ 
	width: <?php echo get_option(THEME_SHORT_NAME.'_second_bottom_slide_width', '155px'); ?> !important; 
	height: auto !important;
}
/* Post/Port Color
   ================================= */
   
.port-title-color a{
	color: <?php echo get_option(THEME_SHORT_NAME.'_port_title_color', '#ffbc2d'); ?> !important;
}
.port-title-color a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_port_title_hover_color', '#ffd885'); ?> !important;
}
.portfolio-thumbnail-context{
	color: <?php echo get_option(THEME_SHORT_NAME.'_port_thumbnail_content', '#d6d6d6'); ?> !important;
}
.portfolio-item{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_port_thumbnail_content_bg', '#000000'); ?> !important;
}
.port-no-image{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_port_no_image_background', '#ffbc2d'); ?> !important;
}
.post-title-color{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_title_color', '#ffbc2d'); ?> !important;
}
.post-title-color a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_title_hover_color', '#ffd885'); ?> !important;
}
.post-info-color, div.custom-sidebar #twitter_update_list{
	color: <?php echo get_option(THEME_SHORT_NAME.'_post_info_color', '#a8a8a8'); ?> !important;
}
div.pagination a:hover, div.pagination span{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_pagination_hover_text', '#000000'); ?>; 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_pagination_hover_background', '#ffbc2d'); ?>; 
}

div.pagination a{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_pagination_text', '#ffffff'); ?>; 
}

.about-author-wrapper{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_post_about_author_color', '#121212'); ?> !important;
}

/* Stunning Text
   ================================= */
.stunning-text-button{
	color: <?php echo get_option(THEME_SHORT_NAME.'_stunning_text_button_color', '#ffffff'); ?> !important;
	<?php $stunning_text_button_color = get_option(THEME_SHORT_NAME.'_stunning_text_button_background', '#ef7f2c'); ?> 
	background-color: <?php echo $stunning_text_button_color ?> !important;
	border: 1px solid <?php echo $stunning_text_button_color ?> !important;
}

/* Footer Color
   ================================= */
.footer-overlay{
	<?php
		$footer_opacity = get_option(THEME_SHORT_NAME . "_footer_opacity", '0.45');
		$footer_opacity_ie =  ((float) $footer_opacity) * 100;
	?>
	opacity: <?php echo $footer_opacity; ?>; 
	filter: alpha(opacity=<?php echo $footer_opacity_ie; ?>);
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_background', '#000000'); ?> !important;
}
.footer-widget-wrapper .custom-sidebar-title{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_title_color', '#ececec'); ?> !important;
}
.footer-wrapper div.tagcloud a{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_divider_color', '#303030'); ?>;
}
.footer-wrapper .gdl-divider,
.footer-wrapper .custom-sidebar.gdl-divider div,
.footer-wrapper .custom-sidebar.gdl-divider ul li{
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_divider_color', '#3b3b3b'); ?> !important;
}
.footer-wrapper, .footer-wrapper table th{
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_content_color', '#ffffff'); ?> !important;
}
.footer-wrapper .post-info-color, div.custom-sidebar #twitter_update_list{
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_content_info_color', '#b1b1b1'); ?> !important;
}
div.footer-wrapper div.contact-form-wrapper input[type="text"], 
div.footer-wrapper div.contact-form-wrapper input[type="password"], 
div.footer-wrapper div.contact-form-wrapper textarea, 
div.footer-wrapper div.custom-sidebar #search-text input[type="text"], 
div.footer-wrapper div.custom-sidebar .contact-widget-whole input, 
div.footer-wrapper div.custom-sidebar .contact-widget-whole textarea {
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_input_text', '#888888'); ?> !important; 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_input_background', '#1f1f1f'); ?> !important;
	border: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_footer_input_border', '#1f1f1f'); ?> !important;
}
div.footer-wrapper a.button, div.footer-wrapper button, div.footer-wrapper button:hover {
	color: <?php echo get_option(THEME_SHORT_NAME.'_footer_button_text', '#7a7a7a'); ?> !important; 
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_footer_button_color', '#222222'); ?> !important;
}
div.copyright-wrapper{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_copyright_text', '#808080'); ?> !important;
}

/* Divider Color
   ================================= */
.scroll-top{ 
	color: <?php echo get_option(THEME_SHORT_NAME.'_back_to_top_text_color', '#ececec'); ?> !important;
}

div.tagcloud a{
	background-color: <?php echo get_option(THEME_SHORT_NAME . "_divider_line", '#383838'); ?>;
}

.gdl-divider,
.custom-sidebar.gdl-divider div,
.custom-sidebar.gdl-divider .custom-sidebar-title,
.custom-sidebar.gdl-divider ul li{
	border-color: <?php echo get_option(THEME_SHORT_NAME . "_divider_line", '#383838'); ?> !important;
}
table th{
	color: <?php echo get_option(THEME_SHORT_NAME.'_table_text_title', '#858585'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_table_title_background', '#2b2b2b'); ?>;
}
table, table tr, table tr td, table tr th{
	border-color: <?php echo get_option(THEME_SHORT_NAME . "_table_border", '#101010'); ?>;
}

/* Testimonial Color
   ================================= */
.testimonial-content{
	color: <?php echo get_option(THEME_SHORT_NAME.'_testimonial_text', '#f7f7f7'); ?> !important;
}
.testimonial-author-name{
	color: <?php echo get_option(THEME_SHORT_NAME.'_testimonial_author', '#f7f7f7'); ?> !important;
}
.testimonial-author-position{
	color: <?php echo get_option(THEME_SHORT_NAME.'_testimonial_position', '#c2c2c2'); ?> !important;
}

/* Tabs Color
   ================================= */
<?php $gdl_tab_border = get_option(THEME_SHORT_NAME.'_tab_border_color', '#242424'); ?>
ul.tabs{
	border-color: <?php echo $gdl_tab_border; ?> !important;
}
ul.tabs li a {
	color: <?php echo get_option(THEME_SHORT_NAME.'_tab_text_color', '#808080'); ?> !important;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_tab_background_color', '#292929'); ?> !important;
	border-color: <?php echo $gdl_tab_border; ?> !important;
}
ul.tabs li a.active {
	color: <?php echo get_option(THEME_SHORT_NAME.'_tab_active_text_color', '#e0e0e0'); ?> !important;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_tab_active_background_color', '#404040'); ?> !important;
}

/* Navigation Color
   ================================= */
<?php if(get_option(THEME_SHORT_NAME.'_main_navigation_gradient', 'enable') == 'enable'){ ?>
div.navigation-wrapper{
	background: url('<?php echo GOODLAYERS_PATH; ?>/images/gradient-top-gray-40px.png') repeat-x; 
}
<?php } ?>
.navigation-wrapper{
	margin-top: <?php echo get_option(THEME_SHORT_NAME.'_navigation_top_margin', '15'); ?>px !important;
	margin-right: <?php echo get_option(THEME_SHORT_NAME.'_navigation_right_margin', '15'); ?>px !important;
}
.top-navigation-wrapper{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_top_navigation_background', '#ffffff'); ?> !important;
}
.top-navigation-wrapper-gimmick{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_top_navigation_bottom_bar', '#dadada'); ?> !important;
}
.navigation-wrapper .sf-menu ul,
.navigation-wrapper .sf-menu ul li{
	border-color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_border', '#dadada'); ?> !important;
}
.sf-menu li li, .sf-menu li ul{
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_background', '#000000'); ?> !important;opacity: 0.8;
}
.navigation-wrapper .sf-menu li a{
	color: <?php echo get_option(THEME_SHORT_NAME.'_main_navigation_text', '#ffffff'); ?> !important;
}
.navigation-wrapper .sf-menu ul a,
.navigation-wrapper .sf-menu .current-menu-item ul a{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_text', '#707070'); ?> !important;
}
.navigation-wrapper .sf-menu ul a:focus,
.navigation-wrapper .sf-menu ul a:active,
.navigation-wrapper .sf-menu ul a:hover,
.navigation-wrapper .sf-menu .current-menu-item ul a:focus,
.navigation-wrapper .sf-menu .current-menu-item ul a:active,
.navigation-wrapper .sf-menu .current-menu-item ul a:hover{
	color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_text_hover', '#616161'); ?> !important;font-weight:700;
}
.navigation-wrapper .sf-menu a:focus, 
.navigation-wrapper .sf-menu a:hover, 
.navigation-wrapper .sf-menu a:active{
	color: <?php echo get_option(THEME_SHORT_NAME.'_main_navigation_text_hover', '#3d3d3d'); ?> !important;
} 
.navigation-wrapper .sf-menu .current-menu-item a {
	color: <?php echo get_option(THEME_SHORT_NAME.'_main_navigation_text_current', '#3d3d3d'); ?> !important;
}
.navigation-wrapper .sf-menu ul .current-menu-item a {
	color: <?php echo get_option(THEME_SHORT_NAME.'_sub_navigation_text_current', '#707070'); ?> !important;
}

/* Button Color
   ================================= */
<?php
	$gdl_button_color = get_option(THEME_SHORT_NAME.'_button_background_color', '#f1f1f1');
	$gdl_button_border = get_option(THEME_SHORT_NAME.'_button_border_color', '#dedede');
	$gdl_button_text = get_option(THEME_SHORT_NAME.'_button_text_color', '#7a7a7a');
	$gdl_button_hover = get_option(THEME_SHORT_NAME.'_button_text_hover_color', '#7a7a7a');
?>
a.button, button, input[type="submit"], input[type="reset"], input[type="button"],
a.gdl-button{
	background-color: <?php echo $gdl_button_color; ?>;
	color: <?php echo $gdl_button_text; ?>;
	border: 1px solid <?php echo $gdl_button_border; ?>
}

a.button:hover, button:hover, input[type="submit"]:hover, input[type="reset"]:hover, input[type="button"]:hover,
a.gdl-button:hover{
	color: <?php echo $gdl_button_hover; ?>;
}
   
/* Contact Form
   ================================= */
div.contact-form-wrapper input[type="text"], 
div.contact-form-wrapper input[type="password"],
div.contact-form-wrapper textarea,
div.custom-sidebar #search-text input[type="text"],
div.custom-sidebar .contact-widget-whole input, 
div.comment-wrapper input[type="text"], input[type="password"], div.comment-wrapper textarea,
div.custom-sidebar .contact-widget-whole textarea,
span.wpcf7-form-control-wrap input[type="text"], 
span.wpcf7-form-control-wrap input[type="password"], 
span.wpcf7-form-control-wrap textarea{
	color: <?php echo get_option(THEME_SHORT_NAME.'_contact_form_text_color', '#888888'); ?>;
	background-color: <?php echo get_option(THEME_SHORT_NAME.'_contact_form_background_color', '#383838'); ?>;
	border: 1px solid <?php echo get_option(THEME_SHORT_NAME.'_contact_form_border_color', '#1f1f1f'); ?>;
}

/* Icon Type (dark/light)
   ================================= */
<?php global $gdl_icon_type; ?>

div.single-port-next-nav .right-arrow{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/arrow-right.png') no-repeat; }
div.single-port-prev-nav .left-arrow{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/arrow-left.png') no-repeat; }

div.single-thumbnail-author,
div.archive-wrapper .blog-item .blog-thumbnail-author,
div.blog-item-holder .blog-item .blog-thumbnail-author{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/author.png') no-repeat 0px 4px; }

div.single-thumbnail-date,
div.custom-sidebar .recent-post-widget-date,
div.archive-wrapper .blog-item .blog-thumbnail-date,
div.blog-item-holder .blog-item .blog-thumbnail-date{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/calendar.png') no-repeat 0px 4px; }

div.single-thumbnail-comment,
div.archive-wrapper .blog-item .blog-thumbnail-comment,
div.blog-item-holder .blog-item .blog-thumbnail-comment,
div.custom-sidebar .recent-post-widget-comment-num{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/comment.png') no-repeat 0px 3px; }

div.single-thumbnail-tag,
div.archive-wrapper .blog-item .blog-thumbnail-tag,
div.blog-item-holder .blog-item .blog-thumbnail-tag{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/tag.png') no-repeat 0px 4px; }

div.custom-sidebar #searchsubmit,	
div.search-wrapper input[type="submit"]{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/find-17px.png') no-repeat center; }	

div.single-port-visit-website{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/link-small.png') no-repeat; }

span.accordion-head-image.active,
span.toggle-box-head-image.active{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/minus-24px.png'); }
span.accordion-head-image,
span.toggle-box-head-image{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/plus-24px.png'); }

div.jcarousellite-nav .prev, 
div.jcarousellite-nav .next{ background-image: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/navigation-20px.png'); } 

div.testimonial-icon{ background: url("<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/quotes-18px.png"); }

div.custom-sidebar ul li{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_icon_type; ?>/arrow4.png') no-repeat 0px 12px; }

/* Footer Icon Type
   ================================= */
<?php global $gdl_footer_icon_type; ?>
div.footer-wrapper div.custom-sidebar ul li { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/arrow4.png') no-repeat 0px 12px; }
div.footer-wrapper div.custom-sidebar #searchsubmit { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/find-17px.png') no-repeat center; }
div.footer-wrapper div.custom-sidebar .recent-post-widget-comment-num { background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/comment.png') no-repeat 0px 3px; }
div.footer-wrapper div.custom-sidebar .recent-post-widget-date{ background: url('<?php echo GOODLAYERS_PATH; ?>/images/icon/<?php echo $gdl_footer_icon_type; ?>/calendar.png') no-repeat 0px 4px; }

/* Elements Shadow
   ================================= */
<?php $gdl_element_shadow = get_option(THEME_SHORT_NAME.'_elements_shadow','#ececec'); ?>

div.gdl-price-item .price-item.active{ 
	-moz-box-shadow: 0px 0px 3px <?php echo $gdl_element_shadow; ?>;
	-webkit-box-shadow: 0px 0px 3px <?php echo $gdl_element_shadow; ?>;
	box-shadow: 0px 0px 3px <?php echo $gdl_element_shadow; ?>;
}