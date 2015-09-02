<?php
/*
 * Plugin Name: WP-RecentlyUpdatedPosts Widget
 * Version: 0.1
 * Plugin URI: http://zww.me/archives/25489
 * Description: 显示最近更新过的文章小工具(widget)，可以设置显示数量，排除多少天内发表的文章。
 * Author: zwwooooo
 * Author URI: http://zww.me/
 */

// Recently Updated Posts function by zwwooooo | zww.me
function wp_rup_by_zww($num=10,$days=7) {
	if( !$wp_rup_by_zww = get_option('wp_rup_by_zww') ) {
		query_posts('post_status=publish&orderby=modified&posts_per_page=-1');
		$i=0;
		while ( have_posts() && $i<$num ) : the_post();
			if (current_time('timestamp') - get_the_time('U') > 60*60*24*$days) {
				$i++;
				$the_title_value=get_the_title();
				$wp_rup_by_zww.='<li><a href="'.get_permalink().'" title="'.$the_title_value.'">'
				.$the_title_value.'</a><span class="updatetime"> ('
				.get_the_modified_time('Y-m-d').')</span></li>';
			}
		endwhile;
		wp_reset_query();
		if ( !empty($wp_rup_by_zww) ) update_option('wp_rup_by_zww', $wp_rup_by_zww);
	}
	$wp_rup_by_zww=($wp_rup_by_zww == '') ? '<li>None data.</li>' : $wp_rup_by_zww;
	echo $wp_rup_by_zww;
}
function clear_wp_rup_by_zww_cache() {
    update_option('wp_rup_by_zww', ''); // 清空 wp_rup_by_zww
}
add_action('save_post', 'clear_wp_rup_by_zww_cache'); // 新发表文章/修改文章时触发更新

class WP_Widget_wp_rup_widget extends WP_Widget
{
	function WP_Widget_wp_rup_widget(){
		$widget_ops = array('classname'=>'widget_wp_rup_by_zww','description'=>'显示最近更新过的文章');
		$this->WP_Widget(false,'最近更新的文章',$widget_ops);
	}
	function form($instance){
		$instance = wp_parse_args( (array)$instance, array('title'=>'','showPosts'=>10,'days'=>7) );
		$title = htmlspecialchars($instance['title']);
		$showPosts = htmlspecialchars($instance['showPosts']);
		$days = htmlspecialchars($instance['days']);
		echo '<p style="text-align:left;"><label for="'.$this->get_field_name('title').'">标题:<input style="width:200px;" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:left;"><label for="'.$this->get_field_name('showPosts').'">文章数量:<input style="width:200px;" id="'.$this->get_field_id('showPosts').'" name="'.$this->get_field_name('showPosts').'" type="text" value="'.$showPosts.'" /></label></p>';
		echo '<p style="text-align:left;"><label for="'.$this->get_field_name('days').'">排除天数:<input style="width:200px" id="'.$this->get_field_id('days').'" name="'.$this->get_field_name('days').'" type="text" value="'.$days.'" /></label></p>';
	}
	function update($new_instance,$old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['showPosts'] = strip_tags(stripslashes($new_instance['showPosts']));
		$instance['days'] = strip_tags(stripslashes($new_instance['days']));
		clear_wp_rup_by_zww_cache();
		return $instance;
	}
	function widget($args,$instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '最近更新的文章' : $instance['title']);
		$showPosts = empty($instance['showPosts']) ? 10 : $instance['showPosts'];
		$days = empty($instance['days']) ? 7 : $instance['days'];
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<ul>';
		wp_rup_by_zww($showPosts,$days);
		echo '</ul>';
		echo $after_widget;
	}
}
function WP_Widget_wp_rup_widgetInit() {
	register_widget('WP_Widget_wp_rup_widget');
}
add_action('widgets_init','WP_Widget_wp_rup_widgetInit');