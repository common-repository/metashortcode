<?php
 /*
Plugin Name: MetaShortcode
Plugin URI:  https://codecide.net/wp/tools/wp/metashortcode/
Description: Use custom fields as shortcode in post content
Version:     1.0
Author:      Codecide
Author URI:  https://plugins.codecide.net/
License: GPL2
Requirements: PHP <= 5.4, jQuery
References:
https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
*/

/**
USAGE:
  - use _[* custom_field_name]_ to display the custom field value
  - optionally use the alternative braces syntax _{* custom_field_name}_ to embed a shortcode within another shortcode attribute. 

Configurable options (admin):
  - Enable/disable the plugin
  - Enable/disable the alternative syntax pre-processor 
*/

defined( 'ABSPATH' ) or exit;

define('metashortcode_pluginpath', dirname(__FILE__).'/');
define('metashortcode_pluginname', 'metashortcode');
require_once(metashortcode_pluginpath.metashortcode_pluginname.'.class.php');

/**
 * Resolves alternative syntax shortcodes
 * @param string $content The content of the post
 * @return string The processed content
 */
function metashortcode_preprocess_altshortcodes( $content ) {
    if ( !(is_single() || is_page() || !is_admin()) ) { return $content; }
    preg_match_all('/\{msc (.*?)\}/', $content, $matches, PREG_SET_ORDER); 
    $source = $target = [];
    foreach($matches as $key=>$match) {
        $source[$key] = $match[0];
    }
    foreach($source as $key=>$src) {
        $shortcode = str_replace( ['{', '}'], ['[', ']'], $src );
        if ($target[$key] = do_shortcode( $shortcode )) {
            $content = str_replace($src, $target[$key], $content);
        }
    }
    return $content;
}

/**
 * Returns the display (string) value of a custom field
 * @param misc $atts Shortcode attributes
 * @return string The value of the custom field, formatted for display
 */
function metashortcode_shortcode($atts) {
    if ( !(is_single() || is_page() || !is_admin()) ) { return; }
    $var = get_post_meta(get_the_ID(), trim($atts[0]), false);
    $value = $var[0];
    switch (gettype($value)) {
        case 'boolean':
            $result = $value ? 'true' : 'false';
            break;
        case 'integer':
        case 'double':
             $result = strval($value);
            break;
        case 'string':
            $result = $value;
            break;
        default: 
            $result = '';
    }
    return $result;
}

new metashortcode(); 
