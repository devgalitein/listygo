<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;

if ( class_exists( 'WP_Customize_Control' ) ) {
    /**
     * Dropdown Select2 Custom Control
     */
    class Customizer_Dropdown_Select2_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'dropdown_select2';
        /**
         * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
         */
        private $multiselect = false;
        /**
         * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
         */
        private $placeholder = 'Please select...';
        /**
         * Constructor
         */
        public function __construct( $manager, $id, $args = array(), $options = array() ) {
            parent::__construct( $manager, $id, $args );
            // Check if this is a multi-select field
            if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
                $this->multiselect = true;
            }
            // Check if a placeholder string has been specified
            if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
                $this->placeholder = $this->input_attrs['placeholder'];
            }
        }
        /**
         * Enqueue our scripts and styles
         */
        public function enqueue() {
            wp_enqueue_script( 'rttheme-select2-js', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/select2.min.js', array( 'jquery' ), '4.0.6', true );
            wp_enqueue_script( 'rttheme-custom-controls-js', trailingslashit( get_template_directory_uri() ) . 'assets/js/customizer.js', array( 'rttheme-select2-js' ), '1.2', true );
            wp_enqueue_style( 'rttheme-select2-css', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/select2.min.css', array(), '4.0.6', 'all' );
            wp_enqueue_style( 'rttheme-custom-controls-css', trailingslashit( get_template_directory_uri() ) . 'assets/css/customizer.css', array(), '1.1', 'all' );
        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
            $defaultValue = $this->value();
            if ( $this->multiselect ) {
                $defaultValue = explode( ',', $this->value() );
            }
            ?>
            <div class="dropdown_select2_control">
                <?php if( !empty( $this->label ) ) { ?>
                    <label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
                        <?php echo esc_html( $this->label ); ?>
                    </label>
                <?php } ?>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>
                <input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr( $this->value() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> />
                <select name="select2-list-<?php echo wp_kses_stripslashes( $this->multiselect ? 'multi[]' : 'single'); ?>" class="customize-control-select2" data-placeholder="<?php echo wp_kses_stripslashes( $this->placeholder ); ?>" <?php echo wp_kses_stripslashes( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
                    <?php
                    if ( !$this->multiselect ) {
                        // When using Select2 for single selection, the Placeholder needs an empty <option> at the top of the list for it to work (multi-selects dont need this)
                        echo '<option></option>';
                    }
                    var_dump($this->choices);
                    foreach ( $this->choices as $key => $value ) {
                        if ( is_array( $value ) ) {
                            echo '<optgroup label="' . esc_attr( $key ) . '">';
                            foreach ( $value as $optgroupkey => $optgroupvalue ) {
                                echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . ( in_array( esc_attr( $optgroupkey ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $optgroupvalue ) . '</option>';
                            }
                            echo '</optgroup>';
                        }
                        else {
                            echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $key ), $defaultValue, false )  . '>' . esc_attr( $value ) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
        }
    }
}