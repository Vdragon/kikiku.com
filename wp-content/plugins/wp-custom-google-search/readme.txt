=== WP Custom Google Search ===
Contributors: ivanra10 
Donate link: https://www.paypal.com/mx/cgi-bin/webscr?cmd=_donations&business=ivanra10@gmail.com&return=http://http://idelgado.net&item_name=Donacion&amount=50&currency_code=MXN
Tags: search, google, custom,
Requires at least: 2.8
Tested up to: 3.2
Stable tag: trunk
License: GPLv3

A customizable google search plugin for posts.


== Description ==

This plugin allows to customize the Custom Google Search inside your posts.

You can change language, theme, a search field for every post.

Requires PHP 5.3.x


== Installation ==

1. Download the plugin and extract its contents.
2. Upload the 'wp-custom-google-search folder' to the '/wp-content/plugins/' directory.
3. Go to Wordpress Plugin Admin Page and activate 'WP Custom Google Search'.
4. Go to Settings -> WP Custom Google Search and set the google search engine ID.
5. Modify your single.php or your custom post page and add this code anywhere in file to show the google search box:
    
    if (function_exists("wpCustomGoogleSearch")) {
        wpCustomGoogleSearch();
    }

For a complete description for the plugin visit http://idelgado.net/plugins/wp-custom-google-search/
    

== Frequently Asked Questions ==

None


== Screenshots ==

1. Admin Options Panel
2. Post Options Panel
3. Custom Google Search in Post


== Changelog ==

= 1.0 =

* First version


== Upgrade Notice ==

