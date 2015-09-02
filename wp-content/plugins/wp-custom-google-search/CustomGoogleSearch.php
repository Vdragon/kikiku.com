<?php
/**
 * Super class
 *
 * Define the global actions for WP Custom Google Search Plugin,
 * used into admin interface and web interface from  Wordpress.
 *
 * @author Iván Delgado <idelgad1@idelgado.net>
 * 
 */
if(!class_exists("CustomGoogleSearch")) {
class CustomGoogleSearch {
    /**
     * Define the main templates directory for plugin
     * @access private
     */
    private static $template_dir = "views";
    
    /**
     * Render a template view.
     *
     * Used to render a given template and data to display a view. Prints the requested view.
     *
     * @param String $tpl Template to display
     * @param Array $data Data to render in template
     * @access protected
     */
    protected function render($tpl, $data = null) {
        $html = "";
        $template = file_get_contents(plugin_dir_path(__FILE__) . self::$template_dir . "/" . $tpl . ".tpl");
        if(isset($template) && isset($data) && is_array($data)) {
            foreach($data as $key=>$value) 
                $template = str_replace("{".$key."}", $value, $template);
            $html = $template;
        }
        print $html;
    }
    
    /**
     * Parse a string.
     *
     * Parse the given string to eliminate some unnecessary characters and symbols.
     *
     * @param String $string The string to parse
     * @return string The modified string
     * @access protected
     **/
    protected function stringParse($string) {
        $string = preg_replace('/[^a-zA-Z0-9_ ñÑáéíóúÁÉÍÓÚ!¡¿?#$%@\[\]\.\(\)%&-]/s', '', $string);
        return $string;
    }   
}
}
?>