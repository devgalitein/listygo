<?php
namespace radiustheme\listygo\Customizer\Controls;

use WP_Customize_Control;

if ( class_exists( 'WP_Customize_Control' ) ) {
    /**
     * Dropdown Posts Custom Control
     */
    class Customizer_Dropdown_Posts_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        public $type = 'dropdown_posts';
        /**
         * Posts
         */
        private $posts = array();
        /**
         * Constructor
         */
        public function __construct( $manager, $id, $args = array(), $options = array() ) {
            parent::__construct( $manager, $id, $args );
            // Get our Posts
            $this->posts = get_posts( $this->input_attrs );
        }
        /**
         * Render the control in the customizer
         */
        public function render_content() {
            ?>
            <div class="dropdown_posts_control">
                <?php if( !empty( $this->label ) ) { ?>
                    <label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
                        <?php echo esc_html( $this->label ); ?>
                    </label>
                <?php } ?>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>
                <select name="<?php echo esc_attr( $this->id ); ?>" id="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?>>
                    <?php
                    if( !empty( $this->posts ) ) {
                        foreach ( $this->posts as $post ) {
                            printf( '<option value="%s" %s>%s</option>',
                                $post->ID,
                                selected( $this->value(), $post->ID, false ),
                                $post->post_title
                            );
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
        }
    }
}