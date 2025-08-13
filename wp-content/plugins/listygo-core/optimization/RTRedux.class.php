<?php
// Security check
defined('ABSPATH') || die();



class RTRedux extends RTOptimizerHooks implements RTOptionFramework{

    public $config;

    public function __construct($config){

        $this->config = $config;

        $this->register();

    }

    public function get_option($id){

        if( isset(DigecoTheme::$options[$id]) && !empty(DigecoTheme::$options[$id]) )
            return DigecoTheme::$options[$id];
        else return '';

    }

    public function register( ){

        // Section
        foreach($this->config['sections'] as $section){

            Redux::setSection( $this->config['ReduxOptionName'], [
                'id' => $section['id'],
                'title' => __($section['title'], $this->config['TextDomain']),
                'icon' => $section['icon'] ?? 'el el-cogs',
            ] );

            // Sub Section
            foreach($section['sub_sections'] as $sub_section){

                Redux::setSection( $this->config['ReduxOptionName'], [
                    'id' => $sub_section['id'],
                    'title' => __($sub_section['title'], $this->config['TextDomain']),
                    'icon' => $sub_section['icon'] ?? 'el el-cog',
                    'subsection' => true,
                    'fields' => $sub_section['fields']
                ] );

            }

        }

    }

}