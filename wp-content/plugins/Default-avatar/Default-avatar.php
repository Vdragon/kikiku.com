<?php
/*
Plugin Name:Default-avatar  
Plugin URI:http://www.lmee.net
Description:设置自定义的头像  
Version:1.0  
Author:叶子
Author URI:http://www.lmee.net
License:GPL  
*/  
add_filter( 'avatar_defaults', 'default_avatar' );   
function default_avatar ( $avatar_defaults ) {
/*默认图片路径*/
$myavatar = get_bloginfo('url'). '/file/imgs/Default-avatar/tweaker.png'; 
/*后台显示名称*/
$avatar_defaults[$myavatar] = "默认头像".$myavatar;
return $avatar_defaults;   
}
