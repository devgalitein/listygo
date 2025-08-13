<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;
/**
 * Separator Custom Control
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class Customizer_Heading_Control2 extends WP_Customize_Control {
        public $type = 'heading';

        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
            wp_enqueue_style('rttheme-custom-controls-css', trailingslashit(get_template_directory_uri()) . 'assets/css/customizer.css', array(), '1.0', 'all');
        }

        public function render_content() {
            ?>
            <h4><?php echo esc_html($this->label); ?></h4>
            <?php
        }
    }
}