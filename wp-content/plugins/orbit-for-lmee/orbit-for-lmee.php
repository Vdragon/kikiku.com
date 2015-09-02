<?php
/*
Plugin Name:orbit-for-lmee  
Plugin URI:http://www.lmee.net
Description:设置自定义的评论栏  
Version:1.0  
Author:叶子
Author URI:http://www.lmee.net
License:GPL  
*/

class orbitWidget extends WP_Widget {
 /*
  ** 声明一个数组$widget_ops，用来保存类名和描述，以便在主题控制面板正确显示小工具信息
  ** $control_ops 是可选参数，用来定义小工具在控制面板显示的宽度和高度
  ** 最后是关键的一步，调用WP_Widget来初始化我们的小工具
  **/
 function orbitWidget(){
  $widget_ops = array('description'=>'用orbit显示幻灯片');
  $this->WP_Widget('orbit', 'ORBIT', $widget_ops);
 }
	function form($instance){
		//or_value:需要内容
		$instance = wp_parse_args((array)$instance,array('or_value'=>'需要内容'));
		$or_value = htmlspecialchars($instance['or_value']);
		echo '<p style="text-align:left;"><label for="'.$this->get_field_name('or_value').'">内容:<input style="width:200px;" id="'.$this->get_field_id('or_value').'" name="'.$this->get_field_name('or_value').'" type="textarea" value="'.$or_value.'" /></label></p>';
	}
	function update($new_instance,$old_instance){
		$instance = $old_instance;
		return $instance;
	}
	function widget($args,$instance) {
		extract($args);
		$or_value = htmlspecialchars($instance['or_value']);
		echo $before_widget;


		$title = '广告区-供应商'; //设置默认的标题
		echo $before_title . $title . $after_title;
		echo $or_value;
		echo '<link rel="stylesheet" href="'.bloginfo('template_url').'/orbit/orbit-1.2.3.css">';
		echo '<script type="text/javascript" src="'.bloginfo('template_url').'/orbit/jquery.orbit-1.2.3.min.js"></script>';
		echo '<script type="text/javascript">';
		echo '$(window).load(function() {';
		echo "$('#orbit_slide').orbit();";
		echo '});';
		echo '</script>';
		echo $after_widget;
	}
}
register_widget('orbitWidget');
?>
