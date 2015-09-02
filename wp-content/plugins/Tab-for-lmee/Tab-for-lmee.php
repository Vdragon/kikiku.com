<?php
/*
Plugin Name:Tab-for-lmee  
Plugin URI:http://lmee.net
Description:设置自定义的评论栏  
Version:1.0  
Author:叶子
Author URI:http://lmee.net
License:GPL  
*/  
function widget_sidebar_lmee_tab() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_lmee_tab($args) {
	  extract($args);
		echo $before_widget;
		
		//$lmee_popular_options = get_option('widget_lmee_popular');
		
		$title = '华丽的选项卡(Tabs)'; //设置默认的标题
		
		echo $before_title . $title . $after_title;
		
		/*$(document).ready(function(){
			$('#wpp-2').addClass("tab");
			$('#%e9%9a%8f%e6%9c%ba%e7%bc%a9%e7%95%a5%e5%9b%be').addClass("tab");
			$('#%e6%9c%80%e7%83%ad%e9%97%a8%e7%bc%a9%e7%95%a5%e5%9b%be').addClass("tab");
		
		
			$('.tab:gt(0)').hide();//gt可理解为不等于,即ok类的集合中匹配不等于0的然后隐藏
			$(".desc:gt(0)").hide();
			var hdw = $('#tab ul.list li');
			hdw.hover(function(){
				$(this).addClass('one').siblings().removeClass('one');//.list集合中的被鼠标移动上的li会添加上one类,siblings方法会吧同辈中的其他li删除one类
				$('.tab').eq(hdw.index(this)).show().siblings().hide();//hdw.index(this)会返回[.list集合中的被鼠标移动上的li在同辈li中的位置]，eq可以理解为等于的意思
				$(".desc").eq(hdw.index(this)).show().siblings().hide();
			});
		});*/
		
		echo '<div id="tabs">';
		echo 	'<ul id="tab-ul">';
		echo 		'<li><a href="#tab2">关注人气</a></li>';
		echo 		'<li><a href="#tab1">日排行</a></li>';
		echo 		'<li><a href="#tab3">总排行</a></li>';
		echo 	'</ul>';
		echo '</div>';
		
		echo $after_widget;
		
	}
	
	register_sidebar_widget('Tab', 'widget_lmee_tab');
	
	function widget_lmee_tab_options() {			
	}
	
	register_widget_control('Tab', 'widget_lmee_tab_options', 300, 90);
}

add_action('plugins_loaded', 'widget_sidebar_lmee_tab');
?>