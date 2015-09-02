<?php
/*
Plugin Name: WP-LinkMove
Plugin URI: http://www.hcsem.com/wp_linkmove/
Description: 链接水平移动 WordPress 插件
Version: 1.1
Author: <a href="http://www.hcsem.com/">黄聪</a>
*/

define('WP_LINKMOVE_OPTION','wp_linkmove_option');

require_once("wp-linkmove-function.php");

/** add a Menu,like "Baidu Sitemap" **/
function wp_linkmove_menu() {
   /** Add a page to the options section of the website **/
   if (current_user_can('manage_options')) 				
 		add_options_page("链接平移设置","链接平移设置", 8, __FILE__, 'wp_linkmove_optionpage');
}

/** custom message **/
function wp_linkmove_topbarmessage($msg) {
	 echo '<div class="updated fade" id="message"><p>' . $msg . '</p></div>';
}

/** wp_linkmove page **/
function wp_linkmove_form() {
	$get_wp_linkmove_options = get_option(WP_LINKMOVE_OPTION);
	//print_r($get_wp_linkmove_options);
	if(empty($get_wp_linkmove_options)){
		global $current_user;
		$wp_linkmove_way = 'R';
		$wp_linkmove_length = "5";
		$wp_linkmove_tag = "a";
	}else{
		list($wp_linkmove_way,$wp_linkmove_length,$wp_linkmove_tag) = explode("|",$get_wp_linkmove_options);
	}

	?>
		<div class="postbox-container" style="width:75%;">
			
	<?php	echo '<div style="background: url('.GetPluginUrl().'img/wp-linkmove.gif) no-repeat;width:75%;height:120px;"><br /></div>'; ?>
		
			<div class="metabox-holder">	
				<div class="meta-box-sortables">			
						
		<div class="tool-box">
			<h3 class="title">参数设置</h3>
			<p>设置链接平移的参数</p>
			<a name="wp_linkmove_options"></a><form name="wp_linkmove_options" method="post" action="">
			<table>
				<tr><td><label for="advanced_options"><h3>全局设置</h3></label></td></tr>
				<tr><td><label for="wp_linkmove_way">链接平移方向</label></td><td><input type="text" size="50" name="wp_linkmove_way" value="<?php echo $wp_linkmove_way;?>" /></td><td><a title="【U】表示上移，【R】表示右移，【D】表示下移，【L】表示左移。默认为右移。">[?]</a><td></tr>
				<tr><td><label for="wp_linkmove_length">移动距离</label></td><td><input type="text" size="50" maxlength="200" name="wp_linkmove_length" value="<?php echo $wp_linkmove_length;?>" /></td><td><a title="移动的距离，单位px，默认5px。">[?]</a><td></tr>
				<tr><td><label for="wp_linkmove_tag">控制标签</label></td><td><input type="text" size="50" maxlength="200" name="wp_linkmove_tag"  value="<?php echo $wp_linkmove_tag;?>" /></td><td><a title="要控制的标签，默认为a，建议不懂编程的朋友不要随意改动。">[?]</a><td></tr>
			</table>
			<p class="submit"><input name="action" type="submit" value="保存设置" class="button-primary" /></p>
			</form>
		</div>

		</div>
			<?php
			/** Show others information **/
			hcsem_text();
			?>
		</div>
		</div>
		</div>
     <?php
}


/** wp_linkmove page **/
function wp_linkmove_optionpage()
{
      /** Perform any action **/
		if(isset($_POST["action"])) {
			update_wp_linkmove();
		}
		
		/** Definition **/
      echo '<div class="wrap"><div style="background: url('.GetPluginUrl().'img/hcsem_name32.png) no-repeat;" class="icon32"><br /></div>';
		echo '<h2>链接平移</h2>';

		/** Introduction **/ 
		echo '<p>启用该插件，可以让你博客的链接当鼠标放置于上方时出现平移效果。</p>';
		
		wp_linkmove_form();
		
		wp_linkmove_sidebar();
}

/** update the options **/
function update_wp_linkmove() {
	if ($_POST['action']=='保存设置'){
		$wp_linkmove_way = $_POST['wp_linkmove_way'];
		$wp_linkmove_length = $_POST['wp_linkmove_length'];
		$wp_linkmove_tag = $_POST['wp_linkmove_tag'];
		
		if($wp_linkmove_way != 'U' && $wp_linkmove_way != 'R' && $wp_linkmove_way != 'D' && $wp_linkmove_way != 'L'){$wp_linkmove_way = 'R';}
		
		$wp_linkmove_options = implode('|',array($wp_linkmove_way,$wp_linkmove_length,$wp_linkmove_tag));
		update_option(WP_LINKMOVE_OPTION,$wp_linkmove_options); 
        wp_linkmove_topbarmessage('更新成功！');
	}
}



function hcsem_text(){
	?>
	<h3>PS:</h3>
	<p>提醒：请确保在header.php文件中添加有wp_head()方法，否则该插件将无法使用，对该插件有何建议，请大家到<a href="http://www.hcsem.com/wp_linkmove/" target="_block">该贴</a>进行回复。</p>
	<?php
}

add_action('admin_menu','wp_linkmove_menu');
/** load the language file **/
add_action('get_header', 'wp_linkmove_jquery');
add_action('wp_head', 'wp_linkmove_action');
function wp_linkmove_jquery() {
	wp_enqueue_script("jquery");
}
function wp_linkmove_action() {
	$get_wp_linkmove_options = get_option(WP_LINKMOVE_OPTION);
	if(empty($get_wp_linkmove_options)){
		global $current_user;
		$wp_linkmove_way = 'R';
		$wp_linkmove_length = "5";
		$wp_linkmove_tag = "a";
	}else{
		list($wp_linkmove_way,$wp_linkmove_length,$wp_linkmove_tag) = explode("|",$get_wp_linkmove_options);
	}
	
	$way = 'left';
	if($wp_linkmove_way == 'U'){$way = 'bottom';}
	if($wp_linkmove_way == 'R'){$way = 'left';}
	if($wp_linkmove_way == 'D'){$way = 'top';}
	if($wp_linkmove_way == 'L'){$way = 'right';}
	
	echo "<style type='text/css'>". $wp_linkmove_tag . " {position:relative;} </style>";
	echo "	<script type='text/javascript'>jQuery(document).ready(function($){
    $('a').hover(function() 
    {
    	$(this).stop().animate({'".$way."': '".$wp_linkmove_length."px'}, 'fast');
    }, function() {
    $(this).stop().animate({'".$way."': '0px'}, 'fast');
    });
    });</script>";
}
?>