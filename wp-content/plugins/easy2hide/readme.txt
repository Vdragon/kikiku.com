=== easy2hide ===
Contributors: dallaslu, peemau
Donate link: http://peema.us/archives/donation.html
Tags: posts, content, hide
Requires at least: 2.7
Tested up to: 3.4
Stable tag: 0.4.5

You could hide some contents in posts, then those who have not replied to any posts will not see them.

== Description ==

Click the `easy2hide` button in HTML editor, and put your contents needed to hide between`<!--easy2hide start{reply_to_this=true}-->` and `<!--easy2hide end-->`.

If you just want to hide some contents from visitors not replying to the CURRENT post, use `<!--easy2hide start{reply_to_this=true}-->some hidden contents<!--easy2hide end-->` in HTML editor; or if you want to hide some contents from visitors not replying to ANY posts, delete the letters between `{` and `}`.<br />
如果你只是想对未在某篇文章发表过评论的访客隐藏内容，使用`<!--easy2hide start{reply_to_this=true}-->一些隐藏内容<!--easy2hide end-->`；或者如果你想对所有未发表过任何评论的访客隐藏内容，删除`{`和`}`之间的词。

* You could learn more by clicking <a href="http://peema.us/archives/easy2hide-for-wordpress-3-3.html" target="_blank">here</a>.
* 详情请访问<a href="http://peema.us/archives/easy2hide-for-wordpress-3-3.html" target="_blank">这里</a>。

* Newer versions are maintained by <a href="http://peema.us/" target="_blank">PeeMau</a>.
* 新版本由<a href="http://peema.us/" target="_blank">PeeMau</a>维护。

####Localization
* English 英文
* Simple Chinese 简体中文
* Translation is always welcome!

####Please!
If you like this plugin, please rate! I need your support!<br />
If you don't rate this plugin as 5/5, please tell me why, so I can improve it, add options and fix bugs.<br />
如果你喜欢这个插件，请给我投票！我需要你的支持！<br />
如果你没有给这个插件评价5/5，请告诉我为什么，从而让我改进和修复错误。

== Installation ==

1. Install this plugin by upload or search & install.
2. Activate it in `Plugins` menu.
1. 上传安装或搜索`TinyMCE Xiami Music`自动安装。
2. 在`插件`菜单中启动。

== Screenshots ==

1. easy2hide button (compatible with WordPress 3.3+).

== Frequently Asked Questions ==

Q : How can I do if I want show the hidden words in rss ?
A : Just Change the code `if (is_feed()){return}` in easy2hide.php.

= How to use ? =

You can write posts like this:

Hello!
<!--easy2hide start{reply_to_this=true}-->Welcome back !<!--easy2hide end-->
Thanks!

== Changelog ==

= 0.4.3 =
* This version is maintained by PeeMau[at]http://peema.us/.
* Support WordPress 3.3+.
* 此版本由PeeMau[at]http://peema.us/维护。
* 支持WordPress 3.3+。

== Upgrade Notice ==

= 0.4.3 =
This version is maintained by PeeMau[at]http://peema.us/.
Support WordPress 3.3+.
此版本由PeeMau[at]http://peema.us/维护。
支持WordPress 3.3+。