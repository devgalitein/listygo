<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use \WP_Widget;
use \RT_Widget_Fields;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

class Newsletter_Widget extends WP_Widget {
	public function __construct() {
		$id = LISTYGO_CORE_THEME_PREFIX . '_newsletter';
		parent::__construct(
            $id, // Base ID
            esc_html__( 'A3: Newsletter', 'listygo-core' ), // Name
            array( 'description' => esc_html__( 'Listygo: Newsletter Widget', 'listygo-core' )
        ) );
	}

	public function widget( $args, $instance ){

		echo wp_kses_post( $args['before_widget'] );

		if ( !empty( $instance['title'] ) ) {
			$html = apply_filters( 'widget_title', $instance['title'] );
			$html = $args['before_title'] . $html .$args['after_title'];
		}
		else {
			$html = '';
		}

		$socials = Helper::socials();
	
		$form = $instance['form'];

		echo wp_kses_stripslashes( $html );	

		?>
		<div class="newsletter-form">
        	<?php echo do_shortcode( $form ); ?>
        </div>
		<?php if ( !empty( $socials ) ) { ?>
			<div class="footer-social">
				<h3 class="footer-social__heading text-white"><?php echo esc_html( RDTListygo::$options['social_label'] ); ?></h3>
				<ul>
					<?php foreach ( $socials as $social ): ?>
					<li>
						<a class="<?php echo esc_attr( $social['class'] ); ?>" href="<?php echo esc_url( $social['url'] ); ?>" target="_blank">
							<i class="<?php echo esc_attr( $social['icon'] ); ?>"></i>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php } ?>
        <?php
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ){
		$instance          = array();

		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		$instance['form'] = ( ! empty( $new_instance['form'] ) ) ? wp_kses_post( $new_instance['form'] ) : '';

		return $instance;
	}

	public function form( $instance ){
		$defaults = array(
			'title' => '',
			'form'  => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = array(
			'title'       => array(
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'type'    => 'text',
			),
			'form' => array(
				'label'   => esc_html__( 'Form Shortcode', 'listygo-core' ),
				'type'    => 'textarea',
			),

		);

		RT_Widget_Fields::display( $fields, $instance, $this );
	}
}