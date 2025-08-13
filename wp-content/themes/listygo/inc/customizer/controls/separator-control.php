<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;
/**
 * Separator Custom Control
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class Customizer_Separator_Control extends WP_Customize_Control {
        public $type = 'separator';

        public function render_content() {
            ?>
            <p>
            <hr></p>
            <?php
        }
    }
}