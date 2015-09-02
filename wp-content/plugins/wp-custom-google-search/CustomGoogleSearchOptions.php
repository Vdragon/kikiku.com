<?php
/**
 * Options Class
 *
 * Define the global options for WP Custom Google Search Plugin. 
 *
 * @author Iván Delgado <idelgad1@idelgado.net>
 * 
 */
if(!class_exists("CustomGoogleSearchOptions")) {
class CustomGoogleSearchOptions {
    /**
     * Defines main slug for the plugin
     * @access public
     */
    const PLUGIN_SLUG = "wp-custom-google-search";
    
    /**
     * Define the language
     * @access public
     */
    const LANG_DIR = "languages";
    
    /**
     * Defines the wordpress admin submenu page where the plugin admin options view will show
     * @access public
     */
    const ADMIN_SUBMENU_PAGE = "options-general.php";
    
     /**
     * Defines the plugin title to show in wordpress submenu
     * @access public
     */
    const ADMIN_SUBMENU_TITLE = "WP Custom Google Search";
    
     /**
     * Defines the plugin title to show into admin options view for this plugin
     * @access public
     */
    const ADMIN_PAGE_TITLE = "WP Custom Google Search";
    
    /**
     * Defines the admin access level to edit the plugin options
     * @access public
     */
    const ADMIN_ACCESS_LEVEL = 10;
    
    /**
     * Defines the slug to access into plugin admin menu view
     * @access public
     */
    const ADMIN_MENU_SLUG = "wp-custom-google-search";
    
    /**
     * Defines the header template name for plugin admin menu view
     * @access public
     */
    const ADMIN_MAIN_HEADER_TPL = "admin-main-header";
    
    /**
     * Defines the body template name for plugin admin menu view
     * @access public
     */
    const ADMIN_MAIN_BODY_TPL = "admin-main-body";
    
    /**
     * Defines the footer template name for plugin admin menu view
     * @access public
     */
    const ADMIN_MAIN_FOOTER_TPL = "admin-main-footer";
    
    /**
     * Defines the donate template name for plugin admin menu view
     * @access public
     */
    const ADMIN_MAIN_DONATE_TPL = "admin-main-donate";
    
    /**
     * Defines the main template name for plugin post metabox view
     * @access public
     */
    const ADMIN_POST_METABOX_TPL = "admin-post-metabox";
    
    /**
     * Defines the name for lang element used into post metabox view
     * @access public
     */
    const ADMIN_SEARCH_TEXT_LANG_ELEMENT = "wpcgs_search_text_lang";
    
    /**
     * Defines the name for theme element used into post metabox view
     * @access public
     */
    const ADMIN_SEARCH_TEXT_THEME_ELEMENT = "wpcgs_search_text_theme";
    
    /**
     * Defines the name for search text element used into post metabox view
     * @access public
     */
    const ADMIN_SEARCH_TEXT_INPUT_ELEMENT = "wpcgs_search_text_input";
    
    /**
     * Defines the name for checkbox element used into post metabox view
     * @access public
     */
    const ADMIN_SEARCH_TEXT_CHECK_ELEMENT = "wpcgs_search_text_check";
    
    /**
     * Defines the database options name for the plugin
     * @access public
     */
    const OPTION_NAME = "_wpcgsearch_options";
    
    /**
     * Defines the database post options name for the plugin
     * @access public
     */
    const USER_GOOGLE_SEARCH_OPTIONS = "_wpcgsearch_user_options";
    
    /**
     * Defines the main template name for plugin post view in web interface
     * @access public
     */
    const USER_GOOGLE_SEARCH_TPL = "user-google-search-box";
    
    
    /**
     * Load the language file for plugin.
     *
     * @access public
     */
    public static function loadLanguage() {
        load_plugin_textdomain( self::PLUGIN_SLUG, false, dirname( plugin_basename(__FILE__) ) . "/" . self::LANG_DIR );
    }
    
    /**
     * Defines template options for google search box view
     *
     * @return array The template options
     * @access public
     */
    public static function getUserGoogleSearchData() {
        return array(
            "USER_GOOGLE_SEARCH_LOADING_TEXT" => __("Loading", self::ADMIN_MENU_SLUG)
        );
    }
    
    /**
     * Defines template options for header admin menu view
     *
     * @return array The template options
     * @access public
     */
    public static function getAdminHeaderData() {
        return array(
            "ADMIN_MENU_OPTIONS_TITLE" => __("WP Custom Google Search - Options", self::ADMIN_MENU_SLUG),
            "ADMIN_MENU_OPTIONS_DESCRIPTION" => __("Please insert a valid google search engine ID.", self::ADMIN_MENU_SLUG),
            "ADMIN_MENU_OPTIONS_ACTION" => "?page=".self::ADMIN_MENU_SLUG."&amp;action=save_options",
            "ADMIN_MENU_OPTIONS_COL1" => "ID",
            "ADMIN_MENU_OPTIONS_COL2" => __("Category", self::ADMIN_MENU_SLUG),
            "ADMIN_MENU_OPTIONS_COL3" => __("Option", self::ADMIN_MENU_SLUG)
        );
    }
    
    /**
     * Defines template options for body admin menu view
     *
     * @return array The template options
     * @access public
     */
    public static function getAdminBodyData() {
        return array(
            "ADMIN_MENU_OPTIONS_GS" => "wpcgs_gs",
            "ADMIN_MENU_OPTIONS_GS_ID_VALUE" => "",
            "ADMIN_MENU_OPTIONS_GS_ID_LABEL" => __("Web Property ID:", self::ADMIN_MENU_SLUG)
         );
    }    
    
    /**
     * Defines template options for footer admin menu view
     *
     * @return array The template options
     * @access public
     */
    public static function getAdminFooterData() {
        return array(
            "ADMIN_MENU_OPTIONS_INPUT_HIDDEN_VALUE" => "save_options",
            "ADMIN_MENU_OPTIONS_INPUT_HIDDEN_NAME" => "action",
            "ADMIN_MENU_OPTIONS_INPUT_SUBMIT_VALUE" => __("Save Options »", self::ADMIN_MENU_SLUG)
        );
    }    
    
    /**
     * Defines template options for donate admin menu view
     *
     * @return array The template options
     * @access public
     */
    public static function getAdminDonateData() {
        return array(
            "ADMIN_MENU_OPTIONS_DONATION_DESC" => __("Please donate for continue developing and supporting the plugin.", self::ADMIN_MENU_SLUG),
            "ADMIN_MENU_OPTIONS_DONATION_LINK" => "http://idelgado.net/plugins/wp-custom-google-search",
            "ADMIN_MENU_OPTIONS_DONATION_VISIT_LABEL" => __("Visit the plugin website", self::ADMIN_MENU_SLUG),
            "ADMIN_MENU_OPTIONS_DONATION_LINK_TEXT" => "iDelgado Plugins",
            "ADMIN_MENU_OPTIONS_DONATION_LINK_PAYPAL" => "https://www.paypal.com/cgi-bin/webscr",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_CMD" => "_donations",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_BUSINESS" => "ivanra10@gmail.com",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_RETURN" => "http://http://idelgado.net",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_ITEM" => __("Donation"),
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_AMOUNT" => "100",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_CURRENCY" => "MXN",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_IMGALT" => "PayPal - The safer, easier way to pay online.",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_IMGSRC" => "https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif",
            "ADMIN_MENU_OPTIONS_DONATION_OPTION_PIXEL_IMGSRC" => "https://www.paypal.com/en_US/i/scr/pixel.gif"
            
        );
    }
        
    /**
     * Defines template options for post metabox view
     *
     * @return array The template options
     * @access public
     */
    public static function getAdminGoogleMetaboxData() {
        return array(
            array(
                "ADMIN_POST_METABOX_ID" => "wpcgs_search_text",
                "ADMIN_POST_METABOX_DESC_LABEL" => __("You should set up a search string to show specific results. If you leave the field empty then the search will be based on post tags.", self::ADMIN_MENU_SLUG),
                "ADMIN_POST_METABOX_LANG_LABEL" => __("Language", self::ADMIN_MENU_SLUG)
            ),
            array(
                array(
                    "VALUE" => "ar",
                    "TEXT" => __("Arabic", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),  
                array(
                    "VALUE" => "bg",
                    "TEXT" => __("Bulgarian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),  
                array(
                    "VALUE" => "ca",
                    "TEXT" => __("Catalan", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),  
                array(
                    "VALUE" => "hr",
                    "TEXT" => __("Croatian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),  
                array(
                    "VALUE" => "zh-Hans",
                    "TEXT" => __("Chinese (Simplified)", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "zh-Hant",
                    "TEXT" => __("Chinese (Traditional)", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "cs",
                    "TEXT" => __("Czech", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "da",
                    "TEXT" => __("Danish", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "nl",
                    "TEXT" => __("Dutch", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "en",
                    "TEXT" => __("English", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "fil",
                    "TEXT" => __("Filipino", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "fi",
                    "TEXT" => __("Finnish", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "fr",
                    "TEXT" => __("French", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "de",
                    "TEXT" => __("German", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "el",
                    "TEXT" => __("Greek", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "he",
                    "TEXT" => __("Hebrew", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "hi",
                    "TEXT" => __("Hindi", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "hu",
                    "TEXT" => __("Hungarian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "id",
                    "TEXT" => __("Indonesian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "it",
                    "TEXT" => __("Italian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "ja",
                    "TEXT" => __("Japanese", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "ko",
                    "TEXT" => __("Korean", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "lv",
                    "TEXT" => __("Latvian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "id",
                    "TEXT" => __("Indonesian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "lt",
                    "TEXT" => __("Lithuanian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "no",
                    "TEXT" => __("Norwegian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "pl",
                    "TEXT" => __("Polish", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "pt",
                    "TEXT" => __("Portuguese", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "ro",
                    "TEXT" => __("Romanian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "ru",
                    "TEXT" => __("Russian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "sr",
                    "TEXT" => __("Serbian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "sk",
                    "TEXT" => __("Slovak", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "sl",
                    "TEXT" => __("Slovenian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "es",
                    "TEXT" => __("Spanish", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "sv",
                    "TEXT" => __("Swedish", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "th",
                    "TEXT" => __("Thai", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "tr",
                    "TEXT" => __("Turkish", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "uk",
                    "TEXT" => __("Ukrainian", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                ),   
                array(
                    "VALUE" => "vi",
                    "TEXT" => __("Vietnamese", self::ADMIN_MENU_SLUG),
                    "SELECTED" => "",
                )
            ),
             array(
                "ADMIN_POST_METABOX_ID" => "wpcgs_search_text",
                "ADMIN_POST_METABOX_THEME_LABEL" => __("Theme", self::ADMIN_MENU_SLUG)
            ),
            array(
                array(
                    "VALUE" => "DEFAULT",
                    "TEXT" => "Default",
                    "SELECTED" => "",
                ),
                array(
                    "VALUE" => "MINIMALIST",
                    "TEXT" => "Minimalist",
                    "SELECTED" => "",
                ),
                array(
                    "VALUE" => "GREENSKY",
                    "TEXT" => "Green Sky",
                    "SELECTED" => "",
                ),
                array(
                    "VALUE" => "BUBBLEGUM",
                    "TEXT" => "Bubblegum",
                    "SELECTED" => "",
                ),
                array(
                    "VALUE" => "ESPRESSO",
                    "TEXT" => "Espresso",
                    "SELECTED" => "",
                ),
                array(
                    "VALUE" => "SHINY",
                    "TEXT" => "Shiny",
                    "SELECTED" => "",
                )  
            ),
            array(
                "ADMIN_POST_METABOX_ID" => "wpcgs_search_text",
                "ADMIN_POST_METABOX_CHECK_LABEL" => __("Choose whether to show the search panel in post", self::ADMIN_MENU_SLUG),
                "ADMIN_POST_METABOX_TEXT_LABEL" => __("Search String", self::ADMIN_MENU_SLUG),
                "ADMIN_SEARCH_TEXT_INPUT" => "",
                "ADMIN_SEARCH_TEXT_CHECK" => ""
            )
        );
    }
    
    /**
     * Used to access to class constants
     *
     * @param String name The constant name
     * @return const The constant class
     * @access private
     */
    private function __get($name){
        if(defined("self::$name"))
            {
            return constant("self::$name");
            }
        trigger_error("$name isn't defined");
    }
}
}
?>