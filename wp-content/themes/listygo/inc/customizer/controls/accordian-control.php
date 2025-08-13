<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;

if ( class_exists( 'WP_Customize_Control' ) ) {
    /**
     * Single Accordion Custom Control
     */
    class Customizer_Single_Accordian_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'single_accordion';
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
            wp_enqueue_script( 'rttheme-custom-controls-js', trailingslashit( get_template_directory_uri() ) . 'assets/js/customizer.js', array( 'jquery' ), '1.2', true );
            wp_enqueue_style( 'rttheme-custom-controls-css', trailingslashit( get_template_directory_uri() ) . 'assets/css/customizer.css', array(), '1.0', 'all' );
            wp_enqueue_style( 'font-awesome-5', trailingslashit( get_template_directory_uri() ) . 'css/fontawesome-all.min.css', array(), '5.0.8', 'all' );
        }

        /**
         * Render the control in the customizer
         */
        public function render_content() {
            $allowed_html = array(
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'class' => array(),
                    'target' => array(),
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array(),
                'i' => array(
                    'class' => array()
                ),
            );
            ?>
            <div class="single-accordion-custom-control">
                <div class="single-accordion-toggle"><?php echo esc_html( $this->label ); ?><span class="accordion-icon-toggle dashicons dashicons-plus"></span></div>
                <div class="single-accordion customize-control-description">
                    <?php
                    if ( is_array( $this->description ) ) {
                        echo '<ul class="single-accordion-description">';
                        foreach ( $this->description as $key => $value ) {
                            echo '<li>' . $key . wp_kses( $value, 'alltext_allow' ) . '</li>';
                        }
                        echo '</ul>';
                    }
                    else {
                        echo wp_kses( $this->description, $allowed_html );
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
}