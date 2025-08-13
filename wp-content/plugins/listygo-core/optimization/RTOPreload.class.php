<?php 
// Security check
defined('ABSPATH') || die();

class RTOPreload {

    public function __construct(){
        add_action('wp_head', [&$this, 'preload']);
        add_action('wp_head', [&$this, 'preconnect']);
    }

    public function preload(){
        global $rt_optimize;
        $urls = array_map('trim', explode(',', ($rt_optimize->options->get_option('rt_preload_list'))));
        if (!empty($urls)) { 
            foreach ($urls as $url) {
                echo "<link rel='preload' href='{$url}' as='font' type='font/woff2' crossorigin />";
            }
        }
    }

    public function preconnect(){
        global $rt_optimize;
        $urls = array_map('trim', explode(',', ($rt_optimize->options->get_option('rt_preconnect_list'))));
        if (!empty($urls)) {
            foreach ($urls as $url) {
                echo "<link rel='preconnect' href='{$url}' />";
            }
        }
    }
}

new RTOPreload();