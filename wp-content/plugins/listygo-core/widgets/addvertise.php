<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use \WP_Widget;
use \RT_Widget_Fields;
use radiustheme\Listygo\Helper;

class Advertise_About_Widget extends WP_Widget {
	public function __construct() {
		$id = LISTYGO_CORE_THEME_PREFIX . '_advertise';
		parent::__construct(
            $id, // Base ID
            esc_html__( 'A8: Advertise', 'listygo-core' ), //Name
            array( 'description' => esc_html__( 'Listygo: Service Widget', 'listygo-core' )
        ) );
	}

	public function widget( $args, $instance ){
		echo wp_kses_post( $args['before_widget'] );

	?>

		<div class="contact-layout-1 advertisement" style="background-image: url(<?php echo wp_get_attachment_image_url($instance['bg_image'],'full') ; ?>)">
		    <div class="contact-content">
		    	 <?php if( !empty( $instance['title'] ) ){ ?>
		        <h2 class="item-title"><?php echo $instance['title']; ?></h2>
		         <?php } if( !empty( $instance['phone'] ) ){ ?>
		        <div class="contact-number">
		            <span class="item-icon"><a href="tel:<?php echo esc_attr( $instance['phone'] ); ?>"><i class="flaticon-phone-call"></i></a></span>
		            <span class="item-number"><?php echo esc_html( $instance['phone'] ); ?></span>
		        </div>
		        <?php } if( !empty( $instance['email'] ) ){ ?>
					<span class="email"><a href="mailto:<?php echo esc_attr( $instance['email'] ); ?>"><?php echo esc_html( $instance['email'] ); ?></a></span>
				<?php } ?>
		    </div>
		</div>

		<?php
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ){
		$instance             = array();
		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['bg_image'] = ( ! empty( $new_instance['bg_image'] ) ) ? sanitize_text_field( $new_instance['bg_image'] ) : '';
		$instance['phone']    = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
		$instance['email']    = ( ! empty( $new_instance['email'] ) ) ? sanitize_email( $new_instance['email'] ) : '';

		return $instance;
	}

	public function form( $instance ){
		$defaults = array(
			'title'   		=> esc_html__( 'Have any Questions ? Call us today.' , 'listygo-core' ),
			'bg_image'    	=> '',
			'phone'   		=> '',
			'email'   		=> '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = array(
			'title'     => array(
				'label' => esc_html__( 'Title', 'listygo-core' ),
				'type'  => 'text',
			),
			'bg_image'    => array(
				'label'   => esc_html__( 'background image', 'listygo-core' ),
				'type'    => 'image',
			),
			'phone'     => array(
				'label' => esc_html__( 'Phone Number', 'listygo-core' ),
				'type'  => 'text',
			),      
			'email'     => array(
				'label' => esc_html__( 'Email', 'listygo-core' ),
				'type'  => 'text',
			),
		);
		RT_Widget_Fields::display( $fields, $instance, $this );
	}
}