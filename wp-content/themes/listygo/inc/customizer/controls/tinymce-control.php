<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;

if ( class_exists( 'WP_Customize_Control' ) ) {
    /**
     * TinyMCE Custom Control (requires WP 4.8+)
     */
    class Customizer_TinyMCE_control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'tinymce_editor';
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue(){
            wp_enqueue_script( 'rttheme-custom-controls-js', trailingslashit( get_template_directory_uri() ) . 'assets/js/customizer.js', array( 'jquery' ), '1.2', true );
            wp_enqueue_style( 'rttheme-custom-controls-css', trailingslashit( get_template_directory_uri() ) . 'assets/css/customizer.css', array(), '1.0', 'all' );
            wp_enqueue_editor();
        }
        /**
         * Pass our TinyMCE toolbar string to JavaScript
         */
        public function to_json() {
            parent::to_json();
            $this->json['rtthemetinymcetoolbar1'] = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';
            $this->json['rtthemetinymcetoolbar2'] = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';
        }
        /**
         * Render the control in the customizer
         */
        public function render_content(){
            ?>
            <div class="tinymce-control">
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>
                <textarea id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-tinymce-editor" <?php $this->link(); ?>><?php echo esc_html( $this->value() ); ?></textarea>
            </div>
            <?php
        }
    }
}
