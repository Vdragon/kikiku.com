<?php
/*
Plugin Name:Notice-for-lmee  
Plugin URI:http://lmee.net
Description:设置自定义的公告
Version:1.0  
Author:叶子
Author URI:http://lmee.net
License:GPL  
*/  
function widget_sidebar_lmee_notice() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_lmee_notice($args) {
	  extract($args);
		echo $before_widget;
		
		$lmee_popular_options = get_option('widget_lmee_popular');
		
		$title = '小家伙说的话(NOTICE)'; //设置默认的标题
		
		echo $before_title . $title . $after_title;

		$page_ID=515; //用来作为公告栏的页面或者文章id
		$num=2; //显示公告的条数
		echo '<ul class="notice" style="padding: 1em 1.4em;">';
		$announcement = '';
		$comments = get_comments("number=$num&post_id=$page_ID");
		if ( !empty($comments) ) {
			foreach ($comments as $comment) {
			$announcement .= '<li>'. convert_smilies($comment->comment_content) . ' <span style="color:#999;">(' . get_comment_date('y/m/d',$comment->comment_ID) . ')</span></li>';
			}
		}
		if ( empty($announcement) ) $announcement = '<li>欢迎来到 LMEE！</li>';
		echo $announcement;
		echo '</ul>';
		if ( is_user_logged_in() ) {
			echo '<p style="text-align:right;"><a href="' .get_page_link($page_ID). '#respond" rel="nofollow">发表公告</a></p>';
		}	

		echo $after_widget;
	}

	register_sidebar_widget('NOTICE', 'widget_lmee_notice');
	
	function widget_lmee_notice_options() {			
	}
	
	register_widget_control('NOTICE', 'widget_lmee_notice_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_lmee_notice');
?>