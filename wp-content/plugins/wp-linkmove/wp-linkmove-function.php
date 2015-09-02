<?php
/** Plugin Author **/
$hc_author = '黄聪';
$hc_authorurl = 'http://www.hcsem.com/';
$hc_plugin = '链接平移';
$hc_pluginversion = '1.0';
$hc_pluginurl = 'http://www.hcsem.com/wp_linkmove/';

if(!function_exists('GetPluginUrl'))
{
	function GetPluginUrl() {
		
		//Try to use WP API if possible, introduced in WP 2.6
		if (function_exists('plugins_url')) return trailingslashit(plugins_url(basename(dirname(__FILE__))));
		
		//Try to find manually... can't work if wp-content was renamed or is redirected
		$path = dirname(__FILE__);
		$path = str_replace("\\","/",$path);
		$path = trailingslashit(get_bloginfo('wpurl')) . trailingslashit(substr($path,strpos($path,"wp-content/")));
		return $path;
	}
}

function wp_linkmove_sidebar() {
	    global $hc_author, $hc_authorurl, $hc_plugin, $hc_pluginversion, $hc_pluginurl;
		?>
		<style type="text/css">
				
		a.hc_button {
			padding:4px;
			display:block;
			padding-left:25px;
			background-repeat:no-repeat;
			background-position:5px 50%;
			text-decoration:none;
			border:none;
		}
		
		a.hc_button:hover {
			border-bottom-width:1px;
		}

		a.hc_pluginHome {
			background-image:url(<?php echo GetPluginUrl(); ?>img/hcsem_name16.ico);
		}
		
		</style>

		<div class="postbox-container" style="width:2%;">
		</div>
		<div class="postbox-container" style="width:21%;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">			

	     <div id="hc_smres" class="postbox">
			<h3 class="hndle"><span >关于链接平移</span></h3>
			  <div class="inside">
			            <a class="hc_button hc_pluginHome" href="<?php echo $hc_authorurl;?>">黄聪（作者博客）</a>
						<a class="hc_button hc_pluginHome" href="<?php echo $hc_pluginurl;?>">链接地址</a>
				</div>
			</div>

			</div>
			</div>
			</div>
		<?php
}

