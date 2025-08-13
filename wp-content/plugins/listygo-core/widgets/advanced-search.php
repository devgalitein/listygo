<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use radiustheme\Listygo\Helper;
use \WP_Widget;
use \RT_Widget_Fields;
use Rtcl\Helpers\Functions;

class Advanced_Search extends WP_Widget {

	public function __construct() {
		$id = LISTYGO_CORE_THEME_PREFIX . '_advanced_search';
		parent::__construct(
			$id, // Base ID
			esc_html__( 'A4: Advanced Search', 'listygo-core' ), // Name
			[
				'description' => esc_html__( 'Add advanced search field', 'listygo-core' ),
			] );
	}

	public function widget( $args, $instance ) {
		$data = [
			'orientation'                   => ! empty( $instance['orientation'] ) ? $instance['orientation'] : 'inline',
			'style'                   		=> ! empty( $instance['style'] ) ? $instance['style'] : 'standard',
			'icon'                          => ! empty( $instance['icon'] ) ? $instance['icon'] : 'icon',
			'can_search_by_keyword'         => ! empty( $instance['search_by_keyword'] ) ? 1 : 0,
			'can_search_by_category'        => ! empty( $instance['search_by_category'] ) ? 1 : 0,
			'can_search_by_location'        => ! empty( $instance['search_by_location'] ) ? 1 : 0,
			'can_search_by_listing_types'   => ! empty( $instance['search_by_listing_types'] ) ? 1 : 0,
			'can_search_by_price'           => ! empty( $instance['search_by_price'] ) ? 1 : 0,
			'can_search_by_custom_field'    => ! empty( $instance['search_by_custom_field'] ) ? 1 : 0,
			'can_search_by_radius_search'   => ! empty( $instance['search_by_radius_search'] ) ? 1 : 0,
			'can_search_by_radius_distance' => ! empty( $instance['search_by_radius_distance'] ) ? 1 : 0,
			'min_price'                     => ! empty( $instance['min_price'] ) ? $instance['min_price'] : 0,
			'max_price'                     => ! empty( $instance['max_price'] ) ? $instance['max_price'] : 5000,
			'instance'                      => $instance,
		];


		$data['args'] = $args;
		$data['data'] = $data;

		$widget_class = '';

		echo $args['before_widget'];
		echo "<div class='" . esc_attr( 'orientation-' . $data['orientation'] . ' ' . $widget_class ) . "'>";

		global $wp;
    	$current_url = home_url( add_query_arg( array(), $wp->request ) );
		?>
		<div class="title-btn">
			<?php 
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				}
			?>
			<button class="reset-btn">
		        <a href="<?php echo esc_url( get_permalink( Functions::get_page_id( 'listings' ) ) ); ?>"">
		            <?php echo esc_html__( 'Clear All', 'listygo-core' ); ?>
		        </a>
		    </button>
		</div>
		
	    <?php
		$template = $data['orientation'] === 'inline' ? 'listing-search-widget' : 'listing-search-widget';
		Helper::get_custom_listing_template( $template, true, $data );
		echo "</div>";
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']                     = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['style']                     = ! empty( $new_instance['style'] ) ? strip_tags( $new_instance['style'] ) : '';
		// $instance['layout']   				   = ( ! empty( $new_instance['layout'] ) ) ? sanitize_text_field( $new_instance['layout'] ) : '';
		$instance['search_by_category']        = ! empty( $new_instance['search_by_category'] ) ? 1 : 0;
		$instance['search_by_location']        = ! empty( $new_instance['search_by_location'] ) ? 1 : 0;
		$instance['search_by_listing_types']   = ! empty( $new_instance['search_by_listing_types'] ) ? 1 : 0;
		$instance['search_by_price']           = ! empty( $new_instance['search_by_price'] ) ? 1 : 0;
		$instance['search_by_keyword']         = ! empty( $new_instance['search_by_keyword'] ) ? 1 : 0;
		$instance['search_by_custom_field']    = ! empty( $new_instance['search_by_custom_field'] ) ? 1 : 0;
		$instance['search_by_radius_search']   = ! empty( $new_instance['search_by_radius_search'] ) ? 1 : 0;
		// $instance['search_by_radius_distance'] = ! empty( $new_instance['search_by_radius_distance'] ) ? 1 : 0;
		$instance['min_price']                 = ! empty( $new_instance['min_price'] ) ? absint( $new_instance['min_price'] ) : 0;
		$instance['max_price']                 = ! empty( $new_instance['max_price'] ) ? absint( $new_instance['max_price'] ) : 5000;

		return $instance;
	}

	public function form( $instance ) {
		// Define the array of defaults
		$defaults = [
			'title'                     => __( 'Advanced Search', 'listygo-core' ),
			'style'        				=> 'standard',
			'search_by_category'        => 1,
			'search_by_location'        => 1,
			'search_by_keyword'         => 1,
			'search_by_listing_types'   => 0,
			'search_by_custom_field'    => 1,
			'search_by_radius_search'   => 1,
			// 'search_by_radius_distance' => 1,
			'search_by_price'           => 1,
		];


		if ( 'local' !== Functions::location_type() ) {
			$defaults['search_by_location'] = 0;
		}

		// Parse incoming $instance into an array and merge it with $defaults
		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		$fields = [
			'title'                     => [
				'label' => esc_html__( 'Title', 'listygo-core' ),
				'type'  => 'text',
			],
			'style'      => array(
				'label'   => esc_html__( 'Search Style', 'listygo-core' ),
				'type'    => 'select',
				'options' => array(
					'popup'      => esc_html__('Popup', 'classified-listing-pro'),
					'suggestion' => esc_html__('Auto Suggestion', 'classified-listing-pro'),
					'dependency' => esc_html__('Dependency Selection', 'classified-listing-pro'),
					'standard'   => esc_html__('Standard', 'classified-listing-pro')
				),
			),
			'search_by_keyword'         => [
				'label' => esc_html__( 'Search by Keyword', 'listygo-core' ),
				'type'  => 'checkbox',
			],
			'search_by_location'        => [
				'label' => esc_html__( 'Search by Local Location', 'listygo-core' ),
				'type'  => 'checkbox',
			],
			'search_by_radius_search'   => [
				'label' => esc_html__( 'Search by Google Location', 'listygo-core' ),
				'type'  => 'checkbox',
			],
			'search_by_category'        => [
				'label' => esc_html__( 'Search by Category', 'listygo-core' ),
				'type'  => 'checkbox',
			],
			'search_by_custom_field'    => [
				'label' => esc_html__( 'Search by Custom Fields', 'listygo-core' ),
				'type'  => 'checkbox',
			],
			'search_by_price'           => [
				'label' => esc_html__( 'Search by Price', 'listygo-core' ),
				'type'  => 'checkbox',
			],
		];

		if ( 'local' !== Functions::location_type() ) {
			unset( $fields['search_by_location'] );
		}

		RT_Widget_Fields::display( $fields, $instance, $this );
	}

}