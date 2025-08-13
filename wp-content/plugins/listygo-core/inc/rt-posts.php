<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'RT_Posts' ) ){

	class RT_Posts {
		
		protected static $instance = null;
		private $post_types = array();
		private $taxonomies = array();

		private function __construct() {
			add_action( 'init', array( $this, 'initialize' ) );
		}

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function initialize() {
			$this->register_taxonomies();
			$this->register_custom_post_types();
		}

		public function add_post_types( $post_types ) {

			foreach ( $post_types as $post_type => $args ) {

				$title = $args['title'];
				$plural_title = empty( $args['plural_title'] ) ? $title : $args['plural_title'];
				
				if ( ! empty( $args['rewrite'] ) ) {
					$args['rewrite'] = array( 'slug' => $args['rewrite'] );
				}

				$labels      = array(
					'name'                     => $plural_title,
					'singular_name'            => $title,
					'add_new'                  => esc_html__( 'Add New', 'listygo-core' ),
					'add_new_item'             => sprintf( esc_html__( 'Add New %s', 'listygo-core' ), $title ),
					'edit_item'                => sprintf( esc_html__( 'Edit %s', 'listygo-core' ), $title ),
					'new_item'                 => sprintf( esc_html__( 'New %s', 'listygo-core' ), $title ),
					'view_item'                => sprintf( esc_html__( 'View %s', 'listygo-core' ), $title ),
					'view_items'               => sprintf( esc_html__( 'View %s', 'listygo-core' ), $plural_title ),
					'search_items'             => sprintf( esc_html__( 'Search %s', 'listygo-core' ), $plural_title ),
					'not_found'                => sprintf( esc_html__( '%s not found', 'listygo-core' ), $plural_title ),
					'not_found_in_trash'       => sprintf( esc_html__( '%s found in Trash', 'listygo-core' ), $plural_title ),
					'parent_item_colon'        => '',
					'all_items'                => sprintf( esc_html__( 'All %s', 'listygo-core' ), $plural_title ),
					'archives'                 => sprintf( esc_html__( '%s Archives', 'listygo-core' ), $title ),
					'attributes'               => sprintf( esc_html__( '%s Attributes', 'listygo-core' ), $title ),
					'insert_into_item'         => sprintf( esc_html__( 'Insert into %s', 'listygo-core' ), $title ),
					'uploaded_to_this_item'    => sprintf( esc_html__( 'Uploaded to this %s', 'listygo-core' ), $title ),
					'filter_items_list'        => sprintf( esc_html__( 'Filter %s list', 'listygo-core' ), $plural_title ),
					'items_list_navigation'    => sprintf( esc_html__( '%s list navigation', 'listygo-core' ), $plural_title ),
					'items_list'               => sprintf( esc_html__( '%s list', 'listygo-core' ), $plural_title ),
					'item_published'           => sprintf( esc_html__( '%s published.', 'listygo-core' ), $title ),
					'item_published_privately' => sprintf( esc_html__( '%s published privately.', 'listygo-core' ), $title ),
					'item_reverted_to_draft'   => sprintf( esc_html__( '%s reverted to draft.', 'listygo-core' ), $title ),
					'item_scheduled'           => sprintf( esc_html__( '%s scheduled.', 'listygo-core' ), $title ),
					'item_updated'             => sprintf( esc_html__( '%s  updated.', 'listygo-core' ), $title ),
					'menu_name'                => $plural_title
				);

				if ( !empty( $args['labels_override'] ) ) {
					$labels = wp_parse_args( $args['labels_override'], $labels );
				}

				$defaults = array(
					'labels'             => $labels,
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'show_in_nav_menus'  => true,
					'query_var'          => true,
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => null,
					'menu_icon'          => null,
					'supports'           => array( 'title', 'thumbnail', 'editor' )
				);

				$args = wp_parse_args( $args, $defaults );
				$this->post_types[ $post_type ] = $args;
			}
		}

		public function add_taxonomies( $taxonomies ) {

			foreach ($taxonomies as $taxonomy => $args ) {

				$title = $args['title'];
				$plural_title = empty( $args['plural_title'] ) ? $title : $args['plural_title'];

				$labels     = array(
					'name'                       => $title,
					'singular_name'              => $title,
					'search_items'               => sprintf( esc_html__( 'Search %s', 'listygo-core' ), $plural_title ),
					'popular_items'              => sprintf( esc_html__( 'Popular %s', 'listygo-core' ), $plural_title ),
					'all_items'                  => sprintf( esc_html__( 'All %s', 'listygo-core' ), $plural_title ),
					'parent_item'                => sprintf( esc_html__( 'Parent %s', 'listygo-core' ), $title ),
					'parent_item_colon'          => sprintf( esc_html__( 'Parent %s:', 'listygo-core' ), $title ),
					'edit_item'                  => sprintf( esc_html__( 'Edit %s', 'listygo-core' ), $title ),
					'view_item'                  => sprintf( esc_html__( 'View %s', 'listygo-core' ), $title ),
					'update_item'                => sprintf( esc_html__( 'Update %s', 'listygo-core' ), $title ),
					'add_new_item'               => sprintf( esc_html__( 'Add New %s', 'listygo-core' ), $title ),
					'new_item_name'              => sprintf( esc_html__( 'New %s Name', 'listygo-core' ), $title ),
					'separate_items_with_commas' => sprintf( esc_html__( 'Separate %s with commas', 'listygo-core' ), $plural_title ),
					'add_or_remove_items'        => sprintf( esc_html__( 'Add or remove %s', 'listygo-core' ), $plural_title ),
					'choose_from_most_used'      => sprintf( esc_html__( 'Choose from the most used %s', 'listygo-core' ), $plural_title ),
					'not_found'                  => sprintf( esc_html__( 'No %s found.', 'listygo-core' ), $plural_title ),
					'no_terms'                   => sprintf( esc_html__( 'No %s', 'listygo-core' ), $plural_title ),
					'items_list_navigation'      => sprintf( esc_html__( '%s list navigation', 'listygo-core' ), $plural_title ),
					'items_list'                 => sprintf( esc_html__( '%s list', 'listygo-core' ), $plural_title ),
					'back_to_items'              => sprintf( esc_html__( '&larr; Back to %s', 'listygo-core' ), $plural_title ),
					'menu_name'                  => $plural_title,
				);

				if ( !empty( $args['labels_override'] ) ) {
					$labels = wp_parse_args( $args['labels_override'], $labels );
				}

				$defaults = array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_in_nav_menus' => true,
					'show_ui'           => null,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => $taxonomy )
				);

				$args = wp_parse_args( $args, $defaults );
				$this->taxonomies[ $taxonomy ] = $args;
			}
		}

		private function register_custom_post_types() {
			$post_types = apply_filters( 'rt_framework_post_types', $this->post_types );
			foreach ( $post_types as $post_type => $args ) {
				register_post_type( $post_type, $args );
			}
		}

		private function register_taxonomies() {
			$taxonomies = apply_filters( 'rt_framework_taxonomies', $this->taxonomies );
			foreach ( $taxonomies as $taxonomy => $args ) {
				register_taxonomy( $taxonomy, $args['post_types'], $args );
			}
		}
	}
}

RT_Posts::getInstance();