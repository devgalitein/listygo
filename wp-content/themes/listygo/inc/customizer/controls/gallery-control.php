<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;
/**
 * Gallery Control
 * Reference: https://wordpress.stackexchange.com/questions/265603/extend-wp-customizer-to-make-multiple-image-selection-possible
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class Customizer_Gallery_Control extends WP_Customize_Control {

        /**
         * Button labels
         */
        public $button_labels = array();

        /**
         * Constructor
         */
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            // Merge the passed button labels with our default labels
            $this->button_labels = wp_parse_args($this->button_labels,
                array(
                    'add' => esc_html__('Add', 'listygo'),
                )
            );
        }

        public function enqueue() {
            wp_enqueue_script( 'rttheme-custom-controls-js', trailingslashit( get_template_directory_uri() ) . 'assets/js/customizer.js', array( 'jquery'), '1.0', true );
            wp_enqueue_style( 'rttheme-custom-controls-css', trailingslashit( get_template_directory_uri() ) . 'assets/css/customizer.css', array(), '1.0', 'all' );
        }

        public function render_content() { ?>
            <?php if (!empty($this->label)) { ?>
                <label>
                    <span class='customize-control-title'><?php echo esc_html( $this->label ); ?></span>
                </label>
            <?php } ?>
            <div>
                <ul class='images'></ul>
            </div>
            <div class='actions'>
                <a class="button-secondary upload"><?php echo esc_html( $this->button_labels['add'] ); ?></a>
            </div>
            <input class="wp-editor-area" id="images-input" type="hidden" <?php $this->link(); ?>>
            <?php
        }
    }

}