<?php
/*
Plugin Name: 缩略图插件 WP-Thumbnails
Plugin URI: http://niaolei.org.cn/wp-thumbnails
Description: Thumbnails for homepage/category, Thumbnails for random/recent/related/popular/single post(s). 首页缩略图、随机缩略图、最新缩略图、分类缩略图、相关缩略图，缩略图可链回原日志或原大图，缩略图尺寸随意设置。
Author: 布谷鸟
Version: 3.2.2
Author URI: http://niaolei.org.cn/

*/


/*  
	Copyright 2008-2010  布谷鸟  (email : 9000birds@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/****************** 博客URL和存储目录 *************************/

$siteurl = get_option('siteurl'); //博客网址
if (substr($siteurl, -1) != "/") $siteurl = $siteurl."/";//保证左斜杠结尾

$newABSPATH = str_replace("\\","/",ABSPATH);  //右斜杠替换为左斜杠
//$rooturl = str_replace("\\","/",getenv("DOCUMENT_ROOT")); //右斜杠替换为左斜杠
$rooturl = $newABSPATH;
if (substr($rooturl, -1) != "/") $rooturl = $rooturl."/";//保证左斜杠结尾

$uploadDir = str_replace($rooturl,"",strstr($newABSPATH,$rooturl)) . "file/imgs/wp-thumbnails/";
$thumbDir = str_replace($rooturl,"",strstr($newABSPATH,$rooturl)) . "file/imgs/wp-thumbnails/ta-thumbnails-cache/";
$downloadDir=str_replace($rooturl,"",strstr($newABSPATH,$rooturl)) . "file/imgs/wp-thumbnails/ta-thumbnails-cache/TAdownload/";


$uploadpath = $rooturl.$uploadDir;
$destpath = $rooturl.$thumbDir;
$downloadpath = $rooturl.$downloadDir;

/**************** 反馈您站点的基本信息 ************************/
// 以下是调试代码，如果插件不能正常工作，请将下面这句注释(echo前面的两个左斜杠)去掉，让插件打印信息，然后反馈到插件主页。

//echo "newABSPATH: ".$newABSPATH."<br>"."rooturl: ".$rooturl."<br>"."thumbDir: ".$thumbDir."<br>"."downloadDir: ".$downloadDir."<br>"."siteurl: ".$siteurl."<br>"."destpath: ".$destpath."<br>"."downloadpath: ".$downloadpath."<br>";


/**************** 以下代码请不要随意改动 ************************/


add_action('init', 'ta_init_textdomain');
function ta_init_textdomain(){
  load_plugin_textdomain('wp_thumbnails',"wp-content/plugins/wp-thumbnails");
}

if(!file_exists($uploadpath)) { 
	if(!(@mkdir($uploadpath,0755))) { 
		if(is_admin()) {
			echo _e("提示：很抱歉，无法创建缩略图目录，请手动创建目录 ", 'wp_thumbnails')."<b>".$uploadpath."</b>"._e("权限设置为755.")."<br>"; 
			return; 
		}
	} 
}

if(!file_exists($destpath)) { 
	if(!(@mkdir($destpath,0755))) { 
		if(is_admin()) {
			echo _e("提示：很抱歉，无法创建缩略图目录，请手动创建目录 ")."<b>".$destpath."</b>"._e("权限设置为755.")."<br>"; 
			return; 
		}
	} 
}

if(!file_exists($downloadpath)) { 
	if(!(@mkdir($downloadpath,0755))) { 
		if(is_admin()) {
			echo _e("提示：很抱歉，无法创建缩略图目录，请手动创建目录 ")."<b>".$downloadpath."</b>"._e("权限设置为755.")."<br>"; 
			return; 
		}
	} 
}

include_once ("ta_homepage.php");
include_once ("ta_post.php");
include_once ("ta_update_meta.php");
include_once ("ta_thumb.php");
include_once ("ta_save_pic.php");
include_once ("ta_widget.php");
include_once ("ta_options.php");
include_once ("ta_clean.php");
include_once ("ta_shortcode.php");
include_once ("ta_excerpt.php");


function wp_thumbnails_head() {
	//$css_url = WP_PLUGIN_URL . '/wp-thumbnails/style.css';
	//echo "\n" . '<!-- START of style generated by wp-thumbnails 3.2.1 ，powered by niaolei.org.cn -->';
	//echo "\n" . '<link rel="stylesheet" href="' . $css_url . '" type="text/css" media="screen" />';
	//echo "\n" . '<!-- END of style generated by wp-thumbnails 3.2.1 ，powered by niaolei.org.cn -->' . "\n";
}

add_action('wp_head', 'wp_thumbnails_head');
add_action('admin_head', 'wp_thumbnails_head'); //用于后台预览

?>