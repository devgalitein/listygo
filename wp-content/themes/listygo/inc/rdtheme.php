<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;


class RDTListygo
{

    protected static $instance = null;

    // Sitewide static variables
    public static $options = null;
    
    // Template specific variables
    public static $layout = null;
    public static $sidebar = null;
    public static $header_top = null;
    public static $tr_header = null;
    public static $header_area = null;
    public static $header_style = null;
    public static $footer_area = null;
    public static $footer_style = null;
    public static $padding_top = null;
    public static $padding_bottom = null;
    public static $has_banner = null;
    public static $has_breadcrumb = null;
    public static $bgimg = null;
    public static $bgcolor = null;
    public static $opacity = null;
    public static $pagebgimg = null;
    public static $pagebgcolor = null;

    private function __construct(){
        add_action('after_setup_theme', array($this, 'set_options'), 1 );
        add_action('customize_preview_init', array($this, 'set_options'), 1 );
    }

    public static function instance(){
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function set_options() {
        $defaults  = rttheme_generate_defaults();
        $newData = [];
        foreach ($defaults as $key => $dValue) {
            $value = get_theme_mod($key, $defaults[$key]);
            $newData[$key] = $value;
        }
        self::$options  = $newData;
    }
}

RDTListygo::instance();

