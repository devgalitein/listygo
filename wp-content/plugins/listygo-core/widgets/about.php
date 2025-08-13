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

class RT_about_Widget extends WP_Widget {
	public function __construct() {
		$id = LISTYGO_CORE_THEME_PREFIX . '_about';
		parent::__construct(
            $id, // Base ID
            esc_html__( 'A1: About', 'listygo-core' ), // Name
            array( 'description' => esc_html__( 'Listygo: About Widget', 'listygo-core' )
        ) );
	}

	public function widget( $args, $instance ){
		echo wp_kses_post( $args['before_widget'] );

		$rdtheme_light_logo = empty( Helper::rt_the_logo_dark() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_dark();
		$socials = Helper::socials();
		$title = $instance['title'];
		$logo = $instance['logo'];
		$desc = $instance['desc'];
		$social = $instance['social'];
		
		?>

		<div class="widget-about">
			<?php if ( $logo == 'enable' ) { ?>
				<div class="footer-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-logo">
						<?php echo wp_kses_post( $rdtheme_light_logo );?>
					</a>
				</div>
			<?php } if (!empty($desc)) { ?>
				<p class="footer-text"><?php echo wp_kses_stripslashes( $desc ); ?></p>
			<?php } if ( $social == 'enable' && !empty($socials) ) { 
				?>
				<div class="footer-social footer-social--style2">
					<?php if (!empty(RDTListygo::$options['social_label'])) { ?>
						<h3 class="footer-social__heading"><?php echo esc_html( RDTListygo::$options['social_label'] ); ?></h3>
					<?php } ?>
					<ul>
						<?php foreach ( $socials as $social ): ?>
						<li>
							<a class="<?php echo esc_attr( $social['class'] ); ?>" href="<?php echo esc_url( $social['url'] ); ?>" rel="nofollow">
								<i class="<?php echo esc_attr( $social['icon'] ); ?>"></i>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php } ?>
		</div>

        <?php 
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ){
		$instance          = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['logo'] = ( ! empty( $new_instance['logo'] ) ) ? sanitize_text_field( $new_instance['logo'] ) : '';
		$instance['desc']  = ( ! empty( $new_instance['desc'] ) ) ? sanitize_text_field( $new_instance['desc'] ) : '';
		$instance['social'] = ( ! empty( $new_instance['social'] ) ) ? sanitize_text_field( $new_instance['social'] ) : '';

		return $instance;
	}

	public function form( $instance ){
		$defaults = array(
			'title'  => '',
			'logo'	 => '',
			'desc'   => '',
			'social' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = array(
			'title'       => array(
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'type'    => 'text',
			),
			'logo'        => array(
				'label'   => esc_html__( 'Logo', 'listygo-core' ),
				'type'    => 'select',
				'options' => array(
					'default' => esc_html__( 'Logo Enable/Disable', 'listygo-core' ),
					'enable' => esc_html__( 'Enable', 'listygo-core' ),
					'disable' => esc_html__( 'Disable', 'listygo-core' )
				)
			),
			'desc'        => array(
				'label'   => esc_html__( 'Description', 'listygo-core' ),
				'type'    => 'textarea',
			),
			'social'        => array(
				'label'   => esc_html__( 'Social', 'listygo-core' ),
				'type'    => 'select',
				'options' => array(
					'default' => esc_html__( 'Social Enable/Disable', 'listygo-core' ),
					'enable' => esc_html__( 'Enable', 'listygo-core' ),
					'disable' => esc_html__( 'Disable', 'listygo-core' ),
				)
			),
		);

		RT_Widget_Fields::display( $fields, $instance, $this );
	}
}