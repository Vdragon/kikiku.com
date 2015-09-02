<?php
/**
 * @package Right Bottom Nav
 */
/*
Plugin Name: Right Bottom Nav
Plugin URI: http://zhengxianjun.sinaapp.com/plugins-wordpress-right-bottom-nav/
Description: 右下角折叠菜单, 由于WordPress基本都引入了JQuery,所以这里不再添加JQuery源文件,请确保引入了JQuery文件, right_bottom_nav的脚本在页面底部. 支持IE7.0+,Chrome;FF未测试. <code>Developed by 郑显军</code>.
Version: 1.0
Author: 郑显军
Author URI: http://zhengxianjun.sinaapp.com/
*/

?>
<?php
/**
 * 添加样式文件
 * */
function rbn_style_action() {
	if (!is_admin()) {
		wp_enqueue_style('right_bottom_nav',get_bloginfo('wpurl').'/wp-content/plugins/right_bottom_nav/images/right_bottom_nav.css', array(),'1.0','screen');
	}
}
add_action('wp_print_styles', 'rbn_style_action');
/**
 * 生成DOM树
 * */
function rbn_dom() {
	if (!is_admin()) {
		$base_url = get_bloginfo('wpurl');
?>
<div id="plugin_rbn">
	<div id="rbn_head_and_body">
		<div id="rbn_head">
			<div class="rbn_post_hot cur_rbn">热点</div><div class="rbn_post_new">最新</div><div class="rbn_post_random">随机</div><div class="rbn_weather">天气</div>
			<div id="rbn_close"></div>
		</div>
		<div id="rbn_body">
			<div class="cur_rbn">
				<ol class="list_hot">

<?php $rbn_posts = new WP_Query('orderby=comment_count&caller_get_posts=4&posts_per_page=3');
while ($rbn_posts->have_posts()) : $rbn_posts->the_post(); ?>
		<li>
			<div class="img_wraper">
	<?php
		preg_match_all('~<img [^\>]*\ />~',get_the_content(),$imgs);
		if(count($imgs[0]) > 0){
			echo $imgs[0][0];
		} else {
			echo '<img alt="" src="',$base_url,'/wp-content/plugins/right_bottom_nav/rbn_default.png','" />';			
		}
	?>
		</div>
		<div class='post_info'>
			<label><a class="plink" href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a></label><br/>
			<label class='author'><?php the_author(); ?></label><label class='ptime'> 于 <?php the_time('m月d日');?></label><br/>
			<label class="pv_and_comment_num"><?php if(function_exists('the_views')){ echo '访问',the_views(),'&nbsp;&nbsp;';} echo comments_number('暂无评论', '评论 1', '评论 %');?></label>
		</div>
	</li>
<?php endwhile;?>
				</ol>
			</div>
			<div>
				<ol class="list_new">
<?php $rbn_posts = new WP_Query('numberposts=10&offset=0');while ($rbn_posts->have_posts()) : $rbn_posts->the_post(); ?><li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title();?></a></li></li><?php endwhile;?>
				</ol>
			</div>
			<div>
				<ul class="list_rand">
<?php $rbn_posts = new WP_Query('numberposts=10&orderby=rand');while ($rbn_posts->have_posts()) : $rbn_posts->the_post(); ?><li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title();?></a><label class="ptime"><?php the_time('m月d日');?></label></li></li><?php endwhile;?>
				</ul>
			</div>
			<div id="rbn_weather_show_div"></div>
		</div>
	</div>
	<div id="rbn_foot">
		<div class="left">
			<ul id="right_bottom_nav">
				<li class="rbn_post_hot cur_rbn"></li><!-- 热点 -->
				<li class="rbn_post_new"></li><!-- 最新 -->
				<li class="rbn_post_random"></li><!-- 随机 -->
				<li class="rbn_weather"></li><!-- 天气 -->
			</ul>
		</div>
		<div class="right">
			<ul id="rbn_foot_right_menu">
				<li id="rbn_top_btn"></li><!-- 回顶部 -->
				<li id="rbn_toggle_btn"></li>
				<li id="rbn_bottom_btn"></li><!-- 到底部 -->
			</ul>
		</div>
	</div>
	<div id="rbn_foot_tip"></div>
</div>
<?php
	}
}
add_action('wp_footer', 'rbn_dom');
/**
 * 基于JQuery,如果没有找到JQuery,则移除节点
 * */
function rbn_script_action() {
	if (!is_admin()) {
?>
<script type="text/javascript" defer="defer">
(function() {
	<?php
		// jQuery冲突的问题
		// 如果要开启title功能,请把注释删掉
		// 并绑定mouseover事件,下面的mouseover已被我删掉
	?>
	var $rbn = $ || jQuery;
	if ($rbn == undefined) {
		// 未引入JQuery文件
		var rbn_dom = document.getElementById('plugin_rbn');
		rbn_dom.parentNode.removeChild(rbn_dom);
		return;
	}
	if ((document.compatMode == "BackCompat")) {
		// 如果值Quirks模式则不显示,本人能力有限
		$rbn('#plugin_rbn').remove();
		return;
	}
	
	/*
	var rbn_show_title_timer = null;
	
	function rbn_show_tip(title) {
		var rbn_tip = $rbn('#rbn_foot_tip');
		
		if (title == undefined || title == null || title == '') {
			rbn_tip.hide();
		} else {
			rbn_tip.html(title);
			
			if (rbn_tip.is(':visible')) {
				clearTimeout(rbn_show_title_timer);
				rbn_show_title_timer = setTimeout("$rbn('#rbn_foot_tip').hide();",3000);
			} else {
				rbn_tip.fadeIn('middle',function () {
					rbn_show_title_timer = setTimeout("$rbn('#rbn_foot_tip').hide();",3000);
				});
			}
		}
	}*/
	$rbn('#rbn_top_btn').click(function() {
		$rbn('html,body').animate({scrollTop:0}, 500);
	});
	$rbn('#rbn_bottom_btn').click(function() {
		$rbn('html,body').animate({scrollTop:$rbn('body').height()}, 500);
	});

	$rbn('#rbn_toggle_btn').click(function() {
		var toggle_btn = $rbn(this);
		if (toggle_btn.hasClass('back')) {
			$rbn('#rbn_head_and_body').fadeOut(500,function () {
				$rbn('#rbn_foot>.left').hide();
				toggle_btn.removeClass('back');
			});
		} else {
			$rbn('#rbn_body>div.cur_rbn').show();
			$rbn('#rbn_foot>.left').toggle(300,function () {
				$rbn('#rbn_head_and_body').slideDown(500,function () {
					toggle_btn.addClass('back');
				});
			});
		}
	});

	$rbn('#rbn_close').click(function() {
		$rbn('#rbn_foot>.left').hide();
		$rbn('#rbn_toggle_btn').removeClass('back');
		$rbn('#rbn_head_and_body').fadeOut(500);
	});
	
	$rbn('#right_bottom_nav>li').click(function() {
		var clc = $rbn(this);
		var clc_index = $rbn('#right_bottom_nav>li').index(clc);
		if (clc_index < 0) {
			return;
		}
		if (!clc.hasClass('cur_rbn')) {
			var bd = $rbn('#rbn_body>div:eq('+clc_index+')');
			
			if (clc.hasClass('rbn_weather') && !bd.hasClass('weather_inited')) {
				// 初始化
				var today = new Date();
				var timestamp_now = today.getTime();
				$rbn.ajax({
					url:"<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/right_bottom_nav/get_weather.php",
					success:function(data) {
						bd.addClass('weather_inited');
						var weather_str = '';
						
						if ((new Date()).getTime() - timestamp_now > 10000) {
							// 超过10s,可能出错,载入weather.com.cn天气窗口
							weather_str = '<iframe src="http://m.weather.com.cn/m/pn12/weather.htm" frameborder="0" width="204" scroll="no"></iframe>';
						} else {
							weather_str = '<div class="list_weather"><div class="weather_city"><span class="weather_city_name">' + data.city + '</span></div>';
							
weather_str += '<table>'
+ '<tr><td><img src="' + data.today.img + '"/><div><span class="weather_date_data">今天' + data.today.condition + '</span></div></td>'
+ '<td><label class="weather_date">' + (today.getMonth() + 1) + '月' + today.getDate() + '号</label>&nbsp;&nbsp;'
+ '<label class="temperature">' + data.today.temper + '°</label><br/>' + data.today.humidity + '<br/>' + data.today.wind + '</td></tr>'
+ '<tr><td><img src="' + data.second.img + '"/><div><span class="weather_date_data">明天' + data.second.condition + '</span></div></td>'
+ '<td><label class="weather_date">' + (today.getMonth() + 1) + '月' + (today.getDate() + 1) + '号</label><label class="temperature">' + data.second.temper + '°C</label><br/></td></tr>'
+ '<tr><td><img src="' + data.third.img + '"/><div><span class="weather_date_data">后天' + data.third.condition + '</span></div></td>'
+ '<td><label class="weather_date">' + (today.getMonth() + 1) + '月' + (today.getDate() + 2) + '号</label><br/><label class="temperature">' + data.third.temper + '°C</label><br/></td></tr>'
+ '</table>';
								
							weather_str += '</div>';
						}
						bd.html(weather_str);

						$rbn('#right_bottom_nav>li.cur_rbn').removeClass('cur_rbn');
						clc.addClass('cur_rbn');
						
						$rbn('#rbn_head>div.cur_rbn').removeClass('cur_rbn');
						$rbn('#rbn_head>div:eq('+clc_index+')').addClass('cur_rbn');

						$rbn('#rbn_body>div.cur_rbn').removeClass('cur_rbn').slideUp(300);
						bd.addClass('cur_rbn').slideDown(300);
					},
					dataType:'json'
				});
			} else {
				$rbn('#right_bottom_nav>li.cur_rbn').removeClass('cur_rbn');
				clc.addClass('cur_rbn');
				
				$rbn('#rbn_head>div.cur_rbn').removeClass('cur_rbn');
				$rbn('#rbn_head>div:eq('+clc_index+')').addClass('cur_rbn');

				$rbn('#rbn_body>div.cur_rbn').removeClass('cur_rbn').slideUp(300);
				bd.addClass('cur_rbn').slideDown(300);
			}
		}
	});
	
	$rbn('#plugin_rbn').mouseleave(function() {
		if ($rbn('#rbn_toggle_btn').hasClass('back')) {
			$rbn('#rbn_head_and_body').fadeOut(500,function () {
				$rbn('#rbn_foot>.left').hide();
				$rbn('#rbn_toggle_btn').removeClass('back');
			});
		}
	});
})();
</script>
<?php
	}
}
add_action('wp_footer', 'rbn_script_action');
?>