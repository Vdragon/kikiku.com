<?php
/*
Plugin Name:By-talk-for-lmee  
Plugin URI:http://lmee.net
Description:设置自定义的评论栏  
Version:1.0  
Author:叶子
Author URI:http://lmee.net
License:GPL  
*/  
function widget_sidebar_lmee_by_talk() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_lmee_by_talk($args) {
	  extract($args);
		echo $before_widget;
		
		$lmee_popular_options = get_option('widget_lmee_popular');
		
		$title = '灌水区-这里很热闹'; //设置默认的标题
		
		echo $before_title . $title . $after_title;

		$page_ID=789; //用来作为公告栏的页面或者文章id
		$num=5; //显示公告的条数
		echo '<ul class="by_talk">';
		$announcement = '';
		$comments = get_comments("number=$num&post_id=$page_ID");
		if ( !empty($comments) ) {
			$flg = 1;
			foreach ($comments as $comment) {
				if($flg ==1){
					$li_title=$comment->comment_content;
					if(similar_text($li_title,"<") > 0){
						//echo "is";
						$start=stripos($li_title,"<");//从前往后查找
						$__li_title=strrev($li_title);//反转字符串
						$length=strlen($li_title) - $start - stripos($__li_title,">");
						$_li_title=substr_replace($li_title,"含引用",$start,$length);
						$_li_html=substr_replace($li_title,"",$start,$length);
						$announcement .= '<li class="by-talk-first"><a href="'.get_page_link($page_ID). '#comment-' . $comment->comment_ID . '" title="'.$_li_title.'" rel="nofollow" target="_blank">'.$_li_html.'</a><br /><span style="color:#999;text-align:right;">('.get_comment_date('Y/m/d H:i',$comment->comment_ID).')</span></li><hr />';
					}else{
						//echo "un";
						$announcement .= '<li class="by-talk-first"><a href="'.get_page_link($page_ID). '#comment-' . $comment->comment_ID . '" title="'.$li_title.'" rel="nofollow" target="_blank">'.convert_smilies($comment->comment_content).'</a><br /><span style="color:#999;text-align:right;">('.get_comment_date('Y/m/d H:i',$comment->comment_ID).')</span></li><hr />';
					}
					$flg++;
				}else{
					$li_title=$comment->comment_content;
					if(similar_text($li_title,"<") > 0){
						//echo "is";
						$start=stripos($li_title,"<");//从前往后查找
						$__li_title=strrev($li_title);//反转字符串
						$length=strlen($li_title) - $start - stripos($__li_title,">");
						$_li_title=substr_replace($li_title,"含引用",$start,$length);
						$_li_html=substr_replace($li_title,"",$start,$length);
						$announcement .= '<li><a href="'.get_page_link($page_ID). '#comment-' . $comment->comment_ID . '" title="'.$_li_title.'" rel="nofollow" target="_blank">'.$_li_html.'</a><br /><span style="color:#999;text-align:right;">('.get_comment_date('Y/m/d H:i',$comment->comment_ID).')</span></li><hr />';
					}else{
						//echo "un";
						$announcement .= '<li><a href="'.get_page_link($page_ID). '#comment-' . $comment->comment_ID . '" title="'.$li_title.'" rel="nofollow" target="_blank">'.convert_smilies($comment->comment_content).'</a><br /><span style="color:#999;text-align:right;">('.get_comment_date('Y/m/d H:i',$comment->comment_ID).')</span></li><hr />';
					}
				}
			}
		}
		//if ( empty($announcement) ) $announcement = '<li>还没有人灌水奥！</li>';
		echo $announcement;
		echo '</ul>';
		echo "<p style='text-align:right;'>[<a href='" .get_page_link($page_ID). "#respond' rel='nofollow'>说两句</a>]</p>";	

		echo $after_widget;
	}
	function return_bool_title($li_title){
		while(strpos($li_title,"<")){
			echo "aaaaaaa";
			return true;
		}
		return false;
	}

	register_sidebar_widget('By-talk', 'widget_lmee_by_talk');
	
	function widget_lmee_by_talk_options() {			
	}
	
	register_widget_control('By-talk', 'widget_lmee_by_talk_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_lmee_by_talk');
?>