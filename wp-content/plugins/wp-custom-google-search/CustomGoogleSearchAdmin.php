<?php
/**
 * Subclass from CustomGoogleSearch
 *
 * Define the main actions for the admin interface in WP Custom Google Search Plugin.
 *
 * @author Iván Delgado <idelgad1@idelgado.net>
 * 
 */
if(!class_exists("CustomGoogleSearchAdmin")) {
require( plugin_dir_path(__FILE__) . "/CustomGoogleSearch.php");
class CustomGoogleSearchAdmin extends CustomGoogleSearch {
    /**
     * Class default construct
     *
     * @access public
     */
    function __construct() { }
    
    /**
     * Initialize admin interface for WP Custom Google Search Plugin.
     *
     * Initialize necessary wordpress hooks: activate and deactivate plugin,
     * create an admin menu, create a metabox to configure post options,
     * define the callback function when the post is saved.
     *
     * @access public
     */
    public function init() {
        add_action("activate_".CustomGoogleSearchOptions::PLUGIN_SLUG."/".CustomGoogleSearchOptions::PLUGIN_SLUG.".php", array(&$this, 'install'));
        add_action("deactivate_".CustomGoogleSearchOptions::PLUGIN_SLUG."/".CustomGoogleSearchOptions::PLUGIN_SLUG.".php", array(&$this, 'uninstall'));
        add_action('admin_menu', array(&$this, 'addAdminMenuView'));
        add_action('add_meta_boxes', array(&$this, 'postMetaboxSearchTextAdd'));
        add_action('save_post', array(&$this, 'postMetaboxSearchTextSave') );  
    }
    
    /**
     * Install the WP Custom Google Search Plugin.
     *
     * Add the config options into wordpress database.
     * 
     * @access public
     */
    public function install() {
        $option = get_option(CustomGoogleSearchOptions::OPTION_NAME);
        if(empty($option))
            add_option(CustomGoogleSearchOptions::OPTION_NAME, "", "", "yes");
    }
    
    /**
     * Uninstall the WP Custom Google Search Plugin.
     *
     * Delete the config options from wordpress database.
     * 
     * @access public
     */
    public function uninstall() {
        $option = get_option(CustomGoogleSearchOptions::OPTION_NAME);
        if(isset($option))
            delete_option(CustomGoogleSearchOptions::OPTION_NAME);
    }
    
    /**
     * Add the plugin admin view into wordpress admin menu.
     *
     * @access public
     */
    public function addAdminMenuView() {
        
        add_submenu_page(
            CustomGoogleSearchOptions::ADMIN_SUBMENU_PAGE,
            CustomGoogleSearchOptions::ADMIN_SUBMENU_TITLE,
            CustomGoogleSearchOptions::ADMIN_PAGE_TITLE,
            CustomGoogleSearchOptions::ADMIN_ACCESS_LEVEL,
            CustomGoogleSearchOptions::ADMIN_MENU_SLUG,
            array(&$this, "getAdminMenuView")
        );
    }
    
    /**
     * Get the plugin admin view.
     *
     * Display the admin view template for the plugin into wordpress menu,
     * render the necessary templates and data for this view.
     *
     * @global array $_POST
     * @access public
     */
    public function getAdminMenuView() {
        $body_data = CustomGoogleSearchOptions::getAdminBodyData();
        switch ($_POST['action']) {
            case "save_options":
		if(isset($_POST['values'][$body_data['ADMIN_MENU_OPTIONS_GS']])) {
                    $admin_options = maybe_serialize( $_POST['values']);
                    update_option(CustomGoogleSearchOptions::OPTION_NAME, $admin_options);
                }
            break;
        }
        $admin_options = get_option(CustomGoogleSearchOptions::OPTION_NAME);
        $admin_options = maybe_unserialize($admin_options);
        if(is_array($admin_options))
            $body_data['ADMIN_MENU_OPTIONS_GS_ID_VALUE'] = $admin_options[$body_data['ADMIN_MENU_OPTIONS_GS']]['ID'];
        $this->render(CustomGoogleSearchOptions::ADMIN_MAIN_HEADER_TPL, CustomGoogleSearchOptions::getAdminHeaderData());
        $this->render(CustomGoogleSearchOptions::ADMIN_MAIN_BODY_TPL, $body_data);
        $this->render(CustomGoogleSearchOptions::ADMIN_MAIN_FOOTER_TPL, CustomGoogleSearchOptions::getAdminFooterData()); 
        $this->render(CustomGoogleSearchOptions::ADMIN_MAIN_DONATE_TPL, CustomGoogleSearchOptions::getAdminDonateData()); 
    }
    
    /**
     * Add the google search post options metabox
     *
     * @access public
     */
    public function postMetaboxSearchTextAdd() {  
       add_meta_box( "wp-cgs", CustomGoogleSearchOptions::ADMIN_PAGE_TITLE, array(&$this, 'postMetaboxSearchText'), 'post', 'normal', 'high' );
    }
    
    /**
     * Display the metabox view template for the plugin into the post editor,
     * render the necessary templates and data for this view.
     * 
     * @global object $post
     * @access public
     */
    public function postMetaboxSearchText() {
        global $post;  
        $user_options = get_post_meta($post->ID, CustomGoogleSearchOptions::USER_GOOGLE_SEARCH_OPTIONS, true);
        $user_options = maybe_unserialize($user_options);
        $data = CustomGoogleSearchOptions::getAdminGoogleMetaboxData();
        $this->render(CustomGoogleSearchOptions::ADMIN_POST_METABOX_TPL.'-0', $data[0]);
        
        $lang_selected = isset($user_options) ? $user_options['lang'] : "";
        foreach($data[1] as $options){
            if($lang_selected == $options['VALUE'])
                $options['SELECTED'] = 'selected="selected"';
            $this->render(CustomGoogleSearchOptions::ADMIN_POST_METABOX_TPL.'-1', $options);
        }
        
        $this->render(CustomGoogleSearchOptions::ADMIN_POST_METABOX_TPL.'-2', $data[2]);
        $theme_selected = isset($user_options) ? $user_options['theme'] : "";
        foreach($data[3] as $options){
            if($theme_selected == $options['VALUE'])
                $options['SELECTED'] = 'selected="selected"';
            $this->render(CustomGoogleSearchOptions::ADMIN_POST_METABOX_TPL.'-1', $options);
        }
        
        $text_value = isset($user_options) ? $user_options['search_text'] : "";
        if(isset($text_value)) {
            $data[4]['ADMIN_SEARCH_TEXT_INPUT'] = $text_value;
        }
        
        $check = isset($user_options) ? $user_options['search_active'] : "";
        if($check == 'on' ) {
            $data[4]['ADMIN_SEARCH_TEXT_CHECK'] = 'checked="yes"';
        }
        $this->render(CustomGoogleSearchOptions::ADMIN_POST_METABOX_TPL.'-3', $data[4]);
    }  
     /**
     * Save the post options for this plugin.
     *
     * It calls when the post is saved. 
     *
     * @global array $_POST
     * @access public
     */
    function postMetaboxSearchTextSave( $post_id ) {
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
        if( !current_user_can( 'edit_post' ) ) return;
        
        if( isset( $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_LANG_ELEMENT] ) )
            $values['lang'] = $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_LANG_ELEMENT];
           
        if( isset( $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_THEME_ELEMENT] ) )
            $values['theme'] = $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_THEME_ELEMENT];
        
        if( isset( $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_INPUT_ELEMENT] ) ) {  
            $values['search_text'] = $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_INPUT_ELEMENT];
        } else {
            $tags_a = wp_get_post_tags($post_id);
            $text = "";
            foreach($tags_a as $tag) {
                $text .= $tag->name . " "; 
            }
            $values['search_text'] = trim($text);
        }
        $values['search_text'] = $this->stringParse($values['search_text']);
        $chk = isset( $_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_CHECK_ELEMENT] ) ? 'on' : 'off';
        $values['search_active'] = esc_attr($_POST[CustomGoogleSearchOptions::ADMIN_SEARCH_TEXT_CHECK_ELEMENT]);
        update_post_meta( $post_id, CustomGoogleSearchOptions::USER_GOOGLE_SEARCH_OPTIONS, maybe_serialize($values));
    }
}
}
?>