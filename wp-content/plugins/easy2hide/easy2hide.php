<?php
/**
 * @package easy2hide
 * @author Dallas Lu & PeeMau
 * @version 0.4.5
 */
/*
Plugin Name: easy2hide
Plugin URI: http://peema.us/archives/easy2hide-for-wordpress-3-3.html
Description: If you just want to hide some contents from visitors not replying to the CURRENT post, use <strong>&lt;!--easy2hide start{reply_to_this=true}--&gt;</strong>some hidden contents<strong>&lt;!--easy2hide end--&gt;</strong> in HTML editor; or if you want to hide some contents from visitors not replying to ANY posts, delete the letters between '<strong>{</strong>' and '<strong>}</strong>'. 如果你只是想对未在某篇文章发表过评论的访客隐藏内容，使用<strong>&lt;!--easy2hide start{reply_to_this=true}--&gt;</strong>隐藏内容<strong>&lt;!--easy2hide end--&gt;</strong>；或者如果你想对所有未发表过任何评论的访客隐藏内容，删除“<strong>{</strong>”和“<strong>}</strong>”之间的词。
Author: Dallas Lu & PeeMau
Version: 0.4.5
Author URI: http://peema.us/
*/

add_filter('the_content', 'easy2hide');
add_filter('comment_text','easy2hide');

if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('easy2hide', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/lang');
}

function easy2hide($content) {

	if (preg_match_all('/<!--easy2hide start{?([\s\S]*?)}?-->([\s\S]*?)<!--easy2hide end-->/i', $content, $matches)) {

		$params = $matches[1][0];
		$defaults = array('reply_to_this' => 'false');
		$params = wp_parse_args($params, $defaults);

		$stats = 'hide';

		if ($params['reply_to_this'] == 'true') {

			global $current_user;
			get_currentuserinfo();

			if ($current_user->ID) {
				$email = $current_user->user_email;
			} else if (isset($_COOKIE['comment_author_email_'.COOKIEHASH])) {
				$email = $_COOKIE['comment_author_email_'.COOKIEHASH];
			}

			$ereg = "^[_\.a-z0-9]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,5}$";
			if (eregi($ereg, $email)) {
				global $wpdb;
				global $id;
				$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_author_email = '".$email."' and comment_post_id='".$id."'and comment_approved = '1'");
				if ($comments) {
					$stats = 'show';
				}
			}

			$tip = __('Sorry, only those who have replied to this post could see the hidden contents.', 'easy2hide');
		} else {
			if (isset($_COOKIE['comment_author_'.COOKIEHASH]) or current_user_can('level_0')) {
				$stats = 'show';
			}
			$tip = __('Sorry, only those who have replied to any posts of this site could see the hidden contents.', 'easy2hide');
		}

		$easy2hide_notice = '<span class="easy2hide_notice">'.$tip.'</span>';
		if ($stats == 'show') {
			$content = str_replace($matches[0], $matches[2], $content);
		} else {
			$content = str_replace($matches[0], $easy2hide_notice, $content);
		}
	}

	//for the easy2hide 0.1
	if (preg_match_all('/\[cookie]([^\[]*)\[\/cookie]/i ', $content, $matches)) {
		$easy2hide_notice = '<span class="easy2hide_notice">' . __('Sorry, only those who have replied to any posts of this site could see the hidden contents.', 'easy2hide') . '</span>';
		if (isset($_COOKIE['comment_author_'.COOKIEHASH]) or current_user_can('level_0')) {
			$content = str_replace($matches[0], $matches[1], $content);
		} else {
			$content = str_replace($matches[0], $easy2hide_notice, $content);
		}
	}
	return $content;
}

add_action('admin_footer', 'easy2hide_footer_admin');
function easy2hide_footer_admin() {
	if ( !strpos($_SERVER['SCRIPT_NAME'], 'post.php') && !strpos($_SERVER['SCRIPT_NAME'], 'post-new.php')) {
		return '';
	}

	// Javascript Code Courtesy Of WP-AddQuicktag (http://bueltge.de/wp-addquicktags-de-plugin/120/)

	//the buttons is wrapped by double "b" in wp2.7.1 by dallaslu @2009/04/29
	global $wp_version;
	$easy2hide_271_hacker = ($wp_version == '2.7.1') ? ".lastChild.lastChild" : "";
	?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		<?php if ( version_compare( $GLOBALS['wp_version'], '3.3alpha', '>=' ) ) : ?>
		edButtons[edButtons.length] = new edButton(
			// id, display, tagStart, tagEnd, access_key, title
			"easy2hide", "easy2hide", "<!--easy2hide start{reply_to_this=true}-->", "<!--easy2hide end-->", "h", "Insert Hidden Contents"
		);
		<?php else : ?>
		if (e2h_toolbar = document.getElementById("ed_toolbar")<?php echo $easy2hide_271_hacker ?>) {
			easy2hideNr = edButtons.length;
			edButtons[easy2hideNr] = new edButton(
				// id, display, tagStart, tagEnd, access_key, title
				"easy2hide", "easy2hide", "<!--easy2hide start{reply_to_this=true}-->", "<!--easy2hide end-->", "h", "Insert Hidden Contents"
			);
			var easy2hideBut = e2h_toolbar.lastChild;
		
			while (easy2hideBut.nodeType != 1) {
				easy2hideBut = easy2hideBut.previousSibling;
			}

			easy2hideBut = easy2hideBut.cloneNode(true);
			easy2hideBut.value = "easy2hide";
			easy2hideBut.title = "Insert Hidden Contents";
			easy2hideBut.onclick = function () {edInsertTag(edCanvas,parseInt(easy2hideNr));}
			e2h_toolbar.appendChild(easy2hideBut);
			easy2hideBut.id = "easy2hide";
		}
		<?php endif; ?>
	});
</script>
<?php } ?>