<?php
if(get_option('upload_path')=='wp-content/uploads' || get_option('upload_path')==null) {
	update_option('upload_path',WP_CONTENT_DIR.'/uploads'); 
}

//自动添加标签属性
add_filter('the_content', 'fancybox_replace');
function fancybox_replace ($content){
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 class="fancybox" data-fancybox-group="gallery"$6>$7</a>';   /*添加class和data-fancybox-group,分组后才会显示前、后图片按钮*/
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}


//添加默认评论头像
add_filter( 'avatar_defaults', 'default_avatar' );
function default_avatar ( $avatar_defaults ) {
	/*默认图片路径*/
	$myavatar = get_bloginfo('url'). '/file/imgs/Default-avatar/tweaker.png'; 
	/*后台显示名称*/
	$avatar_defaults[$myavatar] = "默认头像" . $myavatar;
	return $avatar_defaults;
}


//使用多说服务器代替gravatar服务器，并缓存头像图片
function my_avatar($avatar) {
	
	//$avatar = preg_replace("/http:\/\/(www|\d).gravatar.com/", "http://gravatar.duoshuo.com", $avatar);
	$avatar = preg_replace("/http:\/\/(www|\d).gravatar.com/", "http://en.gravatar.com", $avatar);
	
	$host = get_bloginfo('wpurl');
	$path = ABSPATH .'avatar/';
	$t = 1209600; //这里设定为14天,如果想修改,请以秒为单位自行计算结果
	//没有时创建
	if (!is_dir($path)) {
		mkdir($path, 0755);
		@chmod($path, 0755);
	}
	
	$tmp = strpos($avatar, 'http');
	$url = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
	$tmp = strpos($url, 'avatar/') + 7;
	$file = substr($url, $tmp, strpos($url, "?", $tmp) - $tmp);
	$e = $path . $file . '.jpg';
	
	if (!is_file($e) || (time() - filemtime($e)) > $t) { //创建文件，或者（即第二次加载）修改得到的文件路径为本地路径//当头像不超过前面指定的时间段时不更新
		copy(htmlspecialchars_decode($url), $e);//如果没有这个文件，则从文件流中创建文件到$e
	} else {
		$avatar = str_replace($url, $host . '/avatar/' . $file . '.jpg', $avatar);
	}
	//if (filesize($e) < 500) copy($host . '/file/imgs/Default-avatar/tweaker.png', $e);
	
	return $avatar;
}
add_filter('get_avatar', 'my_avatar');


/*
//修改评论表情调用路径
function upd_smilies_src ($img_src, $img, $siteurl){
	if (strpos($img, 'icon_') === false) {
		$img = 'icon_' . $img;
	}
	if (strpos($img, '.gif') === false) {
		$img = array_shift(explode('.', $img)) . '.gif';
	}
    return get_bloginfo('template_directory') . '/images/smilies/smilies1/' . $img;  //新标签包路径
}
//评论表情改造，如需更换表情，img/smilies/下替换
add_filter('smilies_src', 'upd_smilies_src', 1, 10); 


function comm_smilies(){
	echo "
<script type='text/javascript' language='javascript'>
    function grin(tag) {
    	var myField;
    	tag = ' ' + tag + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
    		myField = document.getElementById('comment');
    	} else {
    		return false;
    	}
    	if (document.selection) {
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    	}
    	else if (myField.selectionStart || myField.selectionStart == '0') {
    		var startPos = myField.selectionStart;
    		var endPos = myField.selectionEnd;
    		var cursorPos = endPos;
    		myField.value = myField.value.substring(0, startPos)
    					  + tag
    					  + myField.value.substring(endPos, myField.value.length);
    		cursorPos += tag.length;
    		myField.focus();
    		myField.selectionStart = cursorPos;
    		myField.selectionEnd = cursorPos;
    	}
    	else {
    		myField.value += tag;
    		myField.focus();
    	}
    }
</script>";
	$a = array( 'mrgreen','razz','sad','smile','oops','grin','eek','???','cool','lol','mad','twisted','roll','wink','idea','arrow','neutral','cry','?','evil','shock','!' );
	$b = array( 'mrgreen','razz','sad','smile','redface','biggrin','surprised','confused','cool','lol','mad','twisted','rolleyes','wink','idea','arrow','neutral','cry','question','evil','eek','exclaim' );
	
	for( $i=0; $i<22; $i++ ){
		echo '<a title="' . $a[$i] . '" href="javascript:grin(' . "':" . $a[$i] . ":'".')"><img src="' . get_bloginfo('template_directory') . '/images/smilies/smilies1/icon_' . $b[$i] . '.gif" /></a>&nbsp;&nbsp;&nbsp;'; //新标签路径
	}
}
//然后在评论模板comments.php放置表情的位置调用comm_smilies()函数
*/


