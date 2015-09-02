<?php
/**
 * Subclass from CustomGoogleSearch
 *
 * Define the main actions for the user interface in WP Custom Google Search Plugin.
 *
 * @author Iván Delgado <idelgad1@idelgado.net>
 * 
 */
if(!class_exists("CustomGoogleSearchUser")) {
require( plugin_dir_path(__FILE__) . "/CustomGoogleSearch.php");
class CustomGoogleSearchUser extends CustomGoogleSearch {
    
    /**
     * Render the google search template for posts.
     *
     * Display the google search view for the current post.
     *
     * @global object $post 
     * @access public
     */
    public static function renderPostGoogleSearch() {
        global $post;
        
        if($post) {
            $body_data = CustomGoogleSearchOptions::getAdminBodyData();
            $admin_options = get_option(CustomGoogleSearchOptions::OPTION_NAME);
            $admin_options = maybe_unserialize($admin_options);
            $user_options = get_post_meta($post->ID, CustomGoogleSearchOptions::USER_GOOGLE_SEARCH_OPTIONS, true);
            $user_options = maybe_unserialize($user_options);
            if(!is_array($admin_options) && !is_array($user_options))
                return false;
           
            if(empty($admin_options[$body_data['ADMIN_MENU_OPTIONS_GS']]['ID']) && empty($user_options['lang'])
               && empty($user_options['theme']) && empty($user_options['search_text'])
               && empty($user_options['search_active']))
                return false;
            
            if($user_options['search_active'] != 'on')
                return false;
            $data = CustomGoogleSearchOptions::getUserGoogleSearchData();
            
            $options = array(
                "GOOGLE_SEARCH_ID" => $admin_options[$body_data['ADMIN_MENU_OPTIONS_GS']]['ID'],            
                "GOOGLE_SEARCH_LANG" => $user_options['lang'],        
                "GOOGLE_SEARCH_THEME" => $user_options['theme'],        
                "GOOGLE_SEARCH_TEXT" => $user_options['search_text'],        
                "GOOGLE_SEARCH_LOADING_TEXT" => $data['USER_GOOGLE_SEARCH_LOADING_TEXT']
            );
            self::render(CustomGoogleSearchOptions::USER_GOOGLE_SEARCH_TPL, $options);   
        }
    }
}
}
?>