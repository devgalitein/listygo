<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;
/**
 * Sortable Repeater Custom Control
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class Customizer_Sortable_Repeater_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'sortable_repeater';
        /**
         * Button labels
         */
        public $button_labels = array();

        /**
         * Constructor
         */
        public function __construct($manager, $id, $args = array(), $options = array()) {
            parent::__construct($manager, $id, $args);
            // Merge the passed button labels with our default labels
            $this->button_labels = wp_parse_args($this->button_labels,
                array(
                    'add' => esc_html__('Add', 'listygo'),
                )
            );
        }

        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
            wp_enqueue_script('rttheme-custom-controls-js', trailingslashit(get_template_directory_uri()) . 'assets/js/customizer.js', array('jquery', 'jquery-ui-core'), '1.2', true);
            wp_enqueue_style('rttheme-custom-controls-css', trailingslashit(get_template_directory_uri()) . 'assets/css/customizer.css', array(), '1.0', 'all');
        }

        /**
         * Render the control in the customizer
         */
        public function render_content() {
            ?>
            <div class="sortable_repeater_control">
                <?php if (!empty($this->label)) { ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php } ?>
                <?php if (!empty($this->description)) { ?>
                    <span class="customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php } ?>
                <input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>"
                       value="<?php echo esc_attr($this->value()); ?>"
                       class="customize-control-sortable-repeater" <?php $this->link(); ?> />
                <div class="sortable">
                    <div class="repeater">
                        <input type="text" value="" class="repeater-input" /><span
                                class="dashicons dashicons-sort"></span><a
                                class="customize-control-sortable-repeater-delete" href="#"><span
                                    class="dashicons dashicons-no-alt"></span></a>
                    </div>
                </div>
                <button class="button customize-control-sortable-repeater-add"
                        type="button"><?php echo wp_kses_stripslashes( $this->button_labels['add'] ); ?></button>
            </div>
            <?php
        }

    }
}