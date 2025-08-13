<?php

namespace Rtcl\Controllers\Settings;

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use Rtcl\Models\RtclEmail;
use Rtcl\Models\SettingsAPI;
use Rtcl\Services\FormBuilder\FBHelper;
use Rtcl\Services\MaxMindDatabaseService;

class AdminSettings extends SettingsAPI {

	protected $tabs = [];
	protected $option_group = [];
	protected $active_tab;
	protected $current_section;
	protected $gateway_temp_desc;
	protected static $instance = null;
	protected $classMap
		= [
			'misc' => MiscSettingsController::class
		];
	/**
	 * @var array|mixed|void
	 */
	protected $subtabs = [];
	public $maxMindDatabaseService;
	const EXTERNAL_IDS = [];

	public function __construct() {
		$this->classMap = apply_filters( 'rtcl_settings_classMap', $this->classMap );
		add_action( 'admin_init', [ $this, 'setTabs' ] );
		add_action( 'admin_init', [ $this, 'save' ] );
		add_action( 'admin_menu', [ $this, 'add_main_menu' ] );
		add_action( 'admin_menu', [ $this, 'add_payment_menu' ], 15 );
		add_action( 'admin_menu', [ $this, 'add_form_builder_menu' ] );
		add_action( 'admin_menu', [ $this, 'add_filter_menu' ] );
		add_action( 'admin_menu', [ $this, 'add_settings_menu' ], 50 );
		add_action( 'admin_menu', [ $this, 'add_import_menu' ], 60 );
		add_action( 'admin_menu', [ $this, 'add_addons_themes__menu' ], 99 );
		add_action( 'admin_menu', [ $this, 'add_listing_types_menu' ], 1 );
		add_action( 'admin_init', [ $this, 'preview_emails' ] );
		add_action( 'admin_init', [ $this, 'generate_rest_api_key' ] );
		add_action( 'rtcl_admin_settings_groups', [ $this, 'setup_settings' ] );
		add_action( 'rtcl_admin_external_settings', [ $this, 'setup_external_settings' ] );
		if ( ! rtcl()->has_pro() ) {
			add_filter( 'plugin_action_links_' . plugin_basename( RTCL_PLUGIN_FILE ), [ $this, 'get_pro_action' ] );
		}
		if ( apply_filters( 'rtcl_settings_link_on_admin_bar', true ) ) {
			add_action( 'wp_before_admin_bar_render', [ $this, 'add_admin_bar' ], 999 );
		}
		add_filter( 'parent_file', [ $this, 'fix_post_type_menu_new_edit_highlight' ] );
		// Custom column in user table
		add_action( 'manage_users_columns', [ $this, 'register_user_ad_count_column' ], 9 );
		add_action( 'manage_users_custom_column', [ $this, 'register_user_ad_count_column_view' ], 10, 3 );

		add_action( 'in_admin_header',
			function () {
				$screen = get_current_screen();
				if ( ( ! empty( $screen->post_type )
					   && in_array( $screen->post_type, [
						rtcl()->post_type,
						rtcl()->post_type_pricing,
						rtcl()->post_type_cfg,
						rtcl()->post_type_payment
					] ) )
				) {
					remove_all_actions( 'admin_notices' );
					remove_all_actions( 'all_admin_notices' );
				}
			}, 1000 );
	}

	public function fix_post_type_menu_new_edit_highlight( $parent_file ) {
		global $submenu_file, $current_screen;

		if ( $current_screen->post_type == rtcl()->post_type_pricing ) {
			$submenu_file = 'edit.php?post_type=' . rtcl()->post_type_pricing;
			$parent_file  = 'rtcl-admin';
		}

		if ( $current_screen->post_type == rtcl()->post_type_payment ) {
			$submenu_file = 'edit.php?post_type=' . rtcl()->post_type_payment;
			$parent_file  = 'rtcl-admin';
		}

		return $parent_file;
	}

	function register_user_ad_count_column( $columns ) {
		$columns['rtcl_user_ad_count'] = apply_filters( 'rtcl_user_ac_count_column_title', esc_html__( 'Listings', 'classified-listing' ) );

		return $columns;
	}

	function register_user_ad_count_column_view( $value, $column_name, $user_id ) {

		if ( $column_name == 'rtcl_user_ad_count' ) {
			$value = count_user_posts( $user_id, rtcl()->post_type );
			if ( $value ) {
				$value = sprintf(
					'<a href="%s" class="edit"><span aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></a>',
					"edit.php?post_type=rtcl_listing",
					$value,
					sprintf(
					/* translators: Hidden accessibility text. %s: Number of posts. */
						_n( '%s listing by this author', '%s posts by this author', $value ),
						number_format_i18n( $value )
					)
				);
			}
		}

		return $value;
	}

	/**
	 * @param bool $new
	 *
	 * @return AdminSettings|null
	 */
	public static function get_instance( $new = false ) {
		// If the single instance hasn't been set, set it now.
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function get_pro_action( $links ) {
		$links[] = '<a target="_blank" href="' . esc_url( 'https://radiustheme.com/demo/wordpress/classified' ) . '">Demo</a>';
		$links[] = '<a target="_blank" href="' . esc_url( 'https://www.radiustheme.com/docs/classified-listing/' ) . '">Documentation</a>';
		$links[] = '<a target="_blank" style="color: #39b54a;font-weight: 700;" href="'
				   . esc_url( 'https://www.radiustheme.com/downloads/classified-listing-pro-wordpress/' ) . '">Get Pro</a>';

		return $links;
	}

	public function add_admin_bar() {
		if ( ! current_user_can( 'manage_rtcl_options' ) ) {
			return;
		}

		global $wp_admin_bar;
		$url  = add_query_arg( [ 'post_type' => rtcl()->post_type ], admin_url( 'edit.php' ) );
		$args = [
			'id'    => rtcl()->post_type,
			'title' => esc_html__( 'Classified Listing', 'classified-listing' ),
			'href'  => $url,
			'meta'  => [ 'class' => sprintf( '%s-admin-toolbar', rtcl()->post_type ) ]
		];
		$wp_admin_bar->add_menu( $args );

		$category_args = [
			'id'     => rtcl()->post_type . "-category",
			'title'  => esc_html__( 'Categories', 'classified-listing' ),
			'href'   => add_query_arg( [
				'taxonomy'  => rtcl()->category,
				'post_type' => rtcl()->post_type
			], admin_url( 'edit-tags.php' ) ),
			'parent' => rtcl()->post_type,
			'meta'   => [ 'class' => sprintf( '%s-admin-toolbar-categories', rtcl()->post_type ) ]
		];

		$wp_admin_bar->add_menu( $category_args );

		$location_args = [
			'id'     => rtcl()->post_type . "-location",
			'title'  => esc_html__( 'Locations', 'classified-listing' ),
			'href'   => add_query_arg( [
				'taxonomy'  => rtcl()->location,
				'post_type' => rtcl()->post_type
			], admin_url( 'edit-tags.php' ) ),
			'parent' => rtcl()->post_type,
			'meta'   => [
				'class' => sprintf( '%s-admin-toolbar-locations', rtcl()->post_type )
			]
		];

		$wp_admin_bar->add_menu( $location_args );

		$listing_types_args = [
			'id'     => rtcl()->post_type . "-listing-types",
			'title'  => esc_html__( 'Listing Types', 'classified-listing' ),
			'href'   => add_query_arg( [
				'post_type' => rtcl()->post_type,
				'page'      => 'rtcl-listing-type'
			], admin_url( 'edit.php' ) ),
			'parent' => rtcl()->post_type,
			'meta'   => [
				'class' => sprintf( '%s-admin-toolbar-listing-types', rtcl()->post_type )
			]
		];

		$wp_admin_bar->add_menu( $listing_types_args );
		if ( ! FBHelper::isEnabled() ) {
			$cfg_args = [
				'id'     => rtcl()->post_type . "-custom-fields",
				'title'  => esc_html__( 'Custom Fields', 'classified-listing' ),
				'href'   => add_query_arg( [
					'post_type' => rtcl()->post_type_cfg
				], admin_url( 'edit.php' ) ),
				'parent' => rtcl()->post_type,
				'meta'   => [
					'class' => sprintf( '%s-admin-toolbar-custom-fields', rtcl()->post_type )
				]
			];

			$wp_admin_bar->add_menu( $cfg_args );
		}

		$pricing_args = [
			'id'     => rtcl()->post_type . "-pricing",
			'title'  => esc_html__( 'Pricing', 'classified-listing' ),
			'href'   => add_query_arg( [
				'post_type' => rtcl()->post_type_pricing
			], admin_url( 'edit.php' ) ),
			'parent' => rtcl()->post_type,
			'meta'   => [
				'class' => sprintf( '%s-admin-toolbar-pricing', rtcl()->post_type )
			]
		];

		$wp_admin_bar->add_menu( $pricing_args );

		$payment_args = [
			'id'     => rtcl()->post_type . "-payment",
			'title'  => esc_html__( 'Payment History', 'classified-listing' ),
			'href'   => add_query_arg( [
				'post_type' => rtcl()->post_type_payment
			], admin_url( 'edit.php' ) ),
			'parent' => rtcl()->post_type,
			'meta'   => [
				'class' => sprintf( '%s-admin-toolbar-payment', rtcl()->post_type )
			]
		];

		$wp_admin_bar->add_menu( $payment_args );

		$settings_args = [
			'id'     => rtcl()->post_type . "-settings",
			'title'  => esc_html__( 'Settings', 'classified-listing' ),
			'href'   => add_query_arg( [
				'page' => 'rtcl-settings'
			], admin_url( 'admin.php' ) ),
			'parent' => rtcl()->post_type,
			'meta'   => [
				'class' => sprintf( '%s-admin-toolbar-settings', rtcl()->post_type )
			]
		];

		$wp_admin_bar->add_menu( $settings_args );

		$settings_args = [
			'id'     => rtcl()->post_type . "-clear-cache",
			'title'  => esc_html__( 'Clear all cache', 'classified-listing' ),
			'href'   => add_query_arg( [
				rtcl()->nonceId    => wp_create_nonce( rtcl()->nonceText ),
				'clear_rtcl_cache' => ''
			], Link::get_current_url() ),
			'parent' => rtcl()->post_type,
			'meta'   => [
				'class' => sprintf( '%s-admin-toolbar-settings', rtcl()->post_type )
			]
		];

		$wp_admin_bar->add_menu( $settings_args );

		do_action( 'rtcl_admin_bar_menu', $wp_admin_bar, rtcl()->post_type );
	}

	public function add_main_menu() {
		add_menu_page(
			__( 'Classified Listing', 'classified-listing' ),
			__( 'Classified Listing', 'classified-listing' ),
			'manage_rtcl_reports',
			'rtcl-admin',
			[ $this, 'display_reports' ],
			RTCL_URL . '/assets/images/icon-20x20.png',
			5
		);
		add_submenu_page(
			'rtcl-admin',
			__( 'Home', 'classified-listing' ),
			__( 'Home', 'classified-listing' ),
			'manage_rtcl_reports',
			'rtcl-admin',
			[ $this, 'display_reports' ],
			1
		);
	}

	public function add_payment_menu() {
		add_submenu_page(
			'rtcl-admin',
			__( 'Payment History', 'classified-listing' ),
			__( 'Payment History', 'classified-listing' ),
			'manage_options',
			'edit.php?post_type=' . rtcl()->post_type_payment,
		);
		add_submenu_page(
			'rtcl-admin',
			__( 'Pricing', 'classified-listing' ),
			__( 'Pricing', 'classified-listing' ),
			'manage_options',
			'edit.php?post_type=' . rtcl()->post_type_pricing,
		);
	}

	public function add_import_menu() {
		add_submenu_page(
			'rtcl-admin',
			__( 'Export / Import', 'classified-listing' ),
			__( 'Export / Import', 'classified-listing' ),
			'manage_rtcl_reports',
			'rtcl-import-export',
			[ $this, 'display_import_export' ]
		);
	}

	public function add_addons_themes__menu() {
		add_submenu_page(
			'rtcl-admin',
			__( 'Get Extensions', 'classified-listing' ),
			__( '<span>Themes & Addons</span>', 'classified-listing' ),
			'manage_options',
			'rtcl-extension',
			[ $this, 'display_extension_view' ]
		);
	}

	public function add_listing_types_menu() {
		add_submenu_page(
			'edit.php?post_type=' . rtcl()->post_type,
			__( 'Listing Types', 'classified-listing' ),
			__( 'Listing Types', 'classified-listing' ),
			'manage_rtcl_options',
			'rtcl-listing-type',
			[ $this, 'display_listing_type' ]
		);
	}

	public function add_form_builder_menu() {

		add_submenu_page(
			'rtcl-admin',
			__( 'Form Builder', 'classified-listing' ),
			__( 'Form Builder', 'classified-listing' ),
			'manage_rtcl_options',
			'rtcl-fb',
			[ $this, 'display_form_builder' ]
		);

	}

	public function add_filter_menu() {
		add_submenu_page(
			'rtcl-admin',
			__( 'Ajax Filter Builder', 'classified-listing' ),
			__( 'Ajax Filter Builder', 'classified-listing' ),
			'manage_rtcl_options',
			'rtcl-ajax-filter',
			[ $this, 'display_ajax_filter' ]
		);

	}

	public function add_settings_menu() {

		add_submenu_page(
			'rtcl-admin',
			__( 'Settings', 'classified-listing' ),
			__( 'Settings', 'classified-listing' ),
			'manage_rtcl_options',
			'rtcl-settings',
			[ $this, 'display_settings_form' ]
		);

	}

	function display_listing_type() {
		require_once RTCL_PATH . 'views/settings/listing-type.php';
	}

	function display_form_builder() {
		?>
		<div id="rtcl-fba-wrap"></div><?php
	}

	function display_ajax_filter() {
		?>
		<div id="rtcl-afb-wrap" class="rtcl-admin-wrap">
			<div class="rtcl-admin-header">
				<h3 class="rtcl-header-title"><?php esc_html_e( 'Manage Filter form', 'classified-listing' ); ?></h3>
			</div>
			<div class="rtcl-admin-settings-wrap">
				<div id="rtcl-filter-settings-wrap">
					<div class="rtcl-filter-list">
						<div class="rtcl-filter-list-wrap">
							<?php
							$filterForms = Functions::get_option( 'rtcl_filter_settings' );
							if ( ! empty( $filterForms ) ) {
								foreach ( $filterForms as $filterId => $filterForm ) {
									echo sprintf( '<a data-id="%s" class="rtcl-filter-action-wrap"><span class="rtcl-filter-name">%s</span><span class="rtcl-filter-actions"><i class="rtcl-filter-edit dashicons dashicons-edit"></i><i class="rtcl-filter-remove dashicons dashicons-remove"></i></span></a>',
										esc_attr( $filterId ), esc_html( $filterForm['name'] ) );
								}
							}
							?>
						</div>
						<a class="rtcl-admin-btn outline block rtcl-filter-add"
						   title="<?php esc_attr_e( 'Add Filter', 'classified-listing' ); ?>">
							<span
								class="dashicons dashicons-plus-alt2"></span> <?php esc_attr_e( 'Add Filter', 'classified-listing' ); ?>
						</a>
					</div>
					<div id="rtcl-filter-wrap"></div>
				</div>
			</div>
		</div>
		<?php
	}

	function display_settings_form() {
		require_once RTCL_PATH . 'views/settings/admin-settings-display.php';
	}

	function display_import_export() {
		require_once RTCL_PATH . 'views/settings/import-export.php';
	}

	function display_reports() {
		require_once RTCL_PATH . 'views/settings/reports.php';
	}

	function display_extension_view() {
		require_once RTCL_PATH . 'views/settings/extensions/extension.php';
	}

	function setup_settings() {
		if ( $this->active_tab == 'payment' && $this->current_section && array_key_exists( $this->current_section, $this->subtabs ) ) {
			$gateway = Functions::get_payment_gateway( $this->current_section );
			if ( $gateway ) {
				$gateway->init_form_fields();
				$gateway->option   = $this->option;
				$this->form_fields = $gateway->form_fields;
			}
		} else {
			$this->set_fields();
		}

		$this->admin_options();
	}

	public function setup_external_settings() {
		if ( $this->active_tab && $this->current_section && array_key_exists( $this->active_tab, $this->tabs )
			 && array_key_exists( $this->current_section, $this->subtabs )
		) {
			$file_name = RTCL_PATH . "views/settings/{$this->active_tab}-{$this->current_section}-settings.php";
		} else {
			$file_name = RTCL_PATH . "views/settings/{$this->active_tab}-settings.php";
		}
		if ( file_exists( $file_name ) ) {
			include $file_name;
		} else {
			echo '<p>No Setting found to load</p>';
		}
	}

	function set_fields() {
		$field = [];
		if ( $this->active_tab && $this->current_section && array_key_exists( $this->active_tab, $this->tabs )
			 && array_key_exists( $this->current_section, $this->subtabs )
		) {
			$file_name = RTCL_PATH . "views/settings/{$this->active_tab}-{$this->current_section}-settings.php";
		} else {
			$file_name = RTCL_PATH . "views/settings/{$this->active_tab}-settings.php";
		}
		if ( file_exists( $file_name ) ) {
			$field = include $file_name;
		}

		if ( $this->current_section && 'tax_rate' === $this->current_section ) {
			include RTCL_PATH . "views/settings/tax-rate-settings.php";
		} else {
			$this->form_fields = apply_filters( 'rtcl_settings_option_fields', $field, $this->active_tab, $this->current_section );
		}
	}

	protected function add_subsections() {
		if ( ! $this->active_tab ) {
			return;
		}
		if ( method_exists( $this, $this->active_tab . '_add_subsections' ) ) {
			$this->{$this->active_tab . '_add_subsections'}();
		} else {
			$sub_sections = apply_filters( 'rtcl_' . $this->active_tab . '_sub_sections', [] );
			if ( is_array( $sub_sections ) && ! empty( $sub_sections ) ) {
				$this->subtabs = $sub_sections;
			}
		}
	}

	protected function general_add_subsections() {
		$sub_sections  = [
			''              => esc_html__( "Listing Settings", 'classified-listing' ),
			'listing_label' => esc_html__( "Listing Labels", 'classified-listing' ),
			'location'      => esc_html__( "Location", 'classified-listing' ),
			'currency'      => esc_html__( "Currency", 'classified-listing' ),
			'social_share'  => esc_html__( "Social Share", 'classified-listing' )
		];
		$sub_sections  = apply_filters( 'rtcl_general_sub_sections', $sub_sections );
		$this->subtabs = $sub_sections;

		return $sub_sections;
	}

	protected function tax_add_subsections() {
		$sub_sections  = [
			''         => esc_html__( "General", 'classified-listing' ),
			'tax_rate' => esc_html__( "Tax Rates", 'classified-listing' )
		];
		$sub_sections  = apply_filters( 'rtcl_tax_sub_sections', $sub_sections );
		$this->subtabs = $sub_sections;

		return $sub_sections;
	}

	protected function payment_add_subsections() {
		$sections         = [ '' => esc_html__( "Checkout option", 'classified-listing' ) ];
		$payment_gateways = rtcl()->payment_gateways();
		foreach ( $payment_gateways as $gateway ) {
			$title                                  = empty( $gateway->method_title ) ? ucfirst( $gateway->id ) : $gateway->method_title;
			$sections[ strtolower( $gateway->id ) ] = esc_html( $title );
		}
		$this->subtabs = $sections;

		return $sections;
	}

	public function payment_sub_section_section_callback() {
		echo "<p>" . wp_kses( $this->gateway_temp_desc, [ 'a' => [ 'href' => [], 'title' => [] ] ] ) . "</p>";
	}

	protected function misc_add_subsections() {
		$sub_sections  = [
			''      => esc_html__( "Misc", 'classified-listing' ),
			'media' => esc_html__( "Media", 'classified-listing' ),
			'map'   => esc_html__( "Map", 'classified-listing' )
		];
		$sub_sections  = apply_filters( 'rtcl_misc_sub_sections', $sub_sections );
		$this->subtabs = $sub_sections;

		return $sub_sections;
	}

	protected function email_add_subsections() {
		$sub_sections  = [
			''              => esc_html__( "Sender Options", 'classified-listing' ),
			'notifications' => esc_html__( "Email Notifications", 'classified-listing' ),
			'templates'     => esc_html__( "Email Templates", 'classified-listing' )
		];
		$sub_sections  = apply_filters( 'rtcl_email_sub_sections', $sub_sections );
		$this->subtabs = $sub_sections;

		return $sub_sections;
	}

	public function save() {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD']
			 || ! isset( $_REQUEST['page'] )
			 || ( isset( $_REQUEST['page'] ) && 'rtcl-settings' !== $_REQUEST['page'] )
		) {
			return;
		}
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'rtcl-settings' ) ) {
			die( esc_html__( 'Action failed. Please refresh the page and retry.', 'classified-listing' ) );
		}
		if ( $this->active_tab === 'payment' && $this->current_section && array_key_exists( $this->current_section, $this->subtabs ) ) {
			$gateway = Functions::get_payment_gateway( $this->current_section );
			if ( $gateway ) {
				$gateway->init_form_fields();
				$gateway->option   = $this->option;
				$this->form_fields = $gateway->form_fields;
			}
		} else {
			$this->set_fields();
		}
		$this->process_admin_options();

		if ( isset( $_GET['section'] ) && 'tax_rate' === $_GET['section'] ) {
			self::save_tax_options();
		}

		self::add_message( __( 'Your settings have been saved.', 'classified-listing' ) );

		// Clear any unwanted data and flush rules.
		update_option( 'rtcl_queue_flush_rewrite_rules', 'yes' );
		rtcl()->query->init_query_vars();
		rtcl()->query->add_endpoints();

		do_action( 'rtcl_admin_settings_saved', $this->option, $this );
	}

	function setTabs() {
		$this->tabs = [
			'general'         => esc_html__( 'General', 'classified-listing' ),
			'moderation'      => esc_html__( 'Classic Form', 'classified-listing' ),
			'archive_listing' => esc_html__( 'All Listings Page', 'classified-listing' ),
			'single_listing'  => esc_html__( 'Listing Details Page', 'classified-listing' ),
			'payment'         => esc_html__( 'Payment', 'classified-listing' ),
			'tax'             => esc_html__( 'Tax', 'classified-listing' ),
			'email'           => esc_html__( 'Email', 'classified-listing' ),
			'account'         => esc_html__( 'Account & Policy', 'classified-listing' ),
			'style'           => esc_html__( 'Style', 'classified-listing' ),
			'misc'            => esc_html__( 'Misc', 'classified-listing' ),
			'advanced'        => esc_html__( 'Page Setup & Permalink', 'classified-listing' ),
			'tools'           => esc_html__( 'Tools', 'classified-listing' ),
			'ai'              => esc_html__( 'AI Integration', 'classified-listing' ),
		];

		$this->tabs = apply_filters( 'rtcl_register_settings_tabs', $this->tabs );

		$this->option_group = [
			'general'         => [
				'title'  => esc_html__( 'General', 'classified-listing' ),
				'subtab' => $this->general_add_subsections()
			],
			'moderation'      => [
				'title'  => esc_html__( 'Classic Form', 'classified-listing' ),
				'subtab' => []
			],
			'archive_listing' => [
				'title'  => esc_html__( 'All Listings Page', 'classified-listing' ),
				'subtab' => []
			],
			'single_listing'  => [
				'title'  => esc_html__( 'Listing Details Page', 'classified-listing' ),
				'subtab' => []
			],
			'payment'         => [
				'title'  => esc_html__( 'Payment', 'classified-listing' ),
				'subtab' => $this->payment_add_subsections()
			],
			'tax'             => [
				'title'  => esc_html__( 'Tax', 'classified-listing' ),
				'subtab' => $this->tax_add_subsections()
			],
			'email'           => [
				'title'  => esc_html__( 'Email', 'classified-listing' ),
				'subtab' => $this->email_add_subsections()
			],
			'account'         => [
				'title'  => esc_html__( 'Account & Policy', 'classified-listing' ),
				'subtab' => []
			],
			'style'           => [
				'title'  => esc_html__( 'Style', 'classified-listing' ),
				'subtab' => []
			],
			'misc'            => [
				'title'  => esc_html__( 'Misc', 'classified-listing' ),
				'subtab' => $this->misc_add_subsections()
			],
			'advanced'        => [
				'title'  => esc_html__( 'Page Setup & Permalink', 'classified-listing' ),
				'subtab' => []
			],
			'tools'           => [
				'title'  => esc_html__( 'Tools', 'classified-listing' ),
				'subtab' => []
			],
			'ai'              => [
				'title'  => esc_html__( 'AI Integration', 'classified-listing' ),
				'subtab' => []
			],
		];
		// Hook to register custom settings group
		$this->option_group = apply_filters( 'rtcl_register_settings_group', $this->option_group );

		// Find the active tab
		$this->option
			= $this->active_tab = ! empty( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->option_group ) ? trim( $_GET['tab'] )
			: 'general'; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		$this->add_subsections();

		if ( ! empty( $this->subtabs ) ) {
			$this->current_section = ! empty( $_GET['section'] ) && array_key_exists( $_GET['section'], $this->subtabs ) ? trim( $_GET['section'] )
				: ''; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			$this->option          = $this->current_section ? $this->option . '_' . $this->current_section : $this->active_tab;
			$this->option          .= "_settings";
			if ( $this->active_tab === 'payment' && $this->current_section ) {
				$this->option = str_replace( "_settings", "", $this->option );
			}
		} else {
			$this->option = $this->option . "_settings";
		}
		if ( $this->active_tab && ! empty( $this->classMap[ $this->active_tab ] ) ) {
			new $this->classMap[ $this->active_tab ]( $this );
		}

	}

	public function preview_emails() {
		if ( isset( $_GET['preview_rtcl_mail'] ) ) {
			if ( ! ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'preview-mail' ) ) ) {
				die( 'Security check' );
			}

			// load the mailer class.
			$mailer = rtcl()->mailer();

			// get the preview email subject.
			$email_heading = __( 'HTML email template', 'classified-listing' );

			// get the preview email content.
			ob_start();
			include( RTCL_PATH . "views/html-email-template-preview.php" );
			$message = ob_get_clean();

			// create a new email.
			$email = new RtclEmail();
			$email->set_heading( $email_heading );

			// wrap the content with the email template and then add styles.
			$message = apply_filters( 'rtcl_mail_content', $message );

			// print the preview email.
			// phpcs:ignore WordPress.Security.EscapeOutput
			echo $message;
			// phpcs:enable
			exit;
		}
	}

	public static function generate_rest_api_key() {
		if ( isset( $_GET['rtcl_generate_rest_api_key'] ) ) {
			if ( ! isset( $_REQUEST['_wpnonce'] )
				 || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'rtcl_generate_rest_api_key' )
			) {
				Functions::add_notice( __( "You are not allow to make this request.", "classified-listing" ), 'error' );
			} else {
				$oldApikey = get_option( 'rtcl_rest_api_key', null );
				update_option( 'rtcl_rest_api_key', wp_generate_uuid4() );
				if ( $oldApikey ) {
					Functions::add_notice( __( "Your Rest API key is regenerated.", "classified-listing" ) );
				} else {
					Functions::add_notice( __( "Your Rest API key is generated.", "classified-listing" ) );
				}
			}
			wp_safe_redirect( admin_url( 'admin.php?page=rtcl-settings&tab=tools' ) );
			exit();
		}
	}

	public function maxMindDatabaseService() {
		$this->maxMindDatabaseService = apply_filters( 'rtcl_maxmind_geolocation_database_service', null );
		if ( null === $this->maxMindDatabaseService ) {
			$prefix = $this->get_option( 'maxmind_database_prefix' );
			if ( empty( $prefix ) ) {
				$prefix = wp_generate_password( 32, false );
				$this->update_option( 'maxmind_database_prefix', $prefix );
			}
			$this->maxMindDatabaseService = new MaxMindDatabaseService( $prefix );
		}

		return $this->maxMindDatabaseService;
	}

	public static function save_tax_options() {
		global $wpdb;

		$countries    = array_map( 'sanitize_text_field', $_POST['rtcl_tax_rate_country'] ?? [] );
		$states       = array_map( 'sanitize_text_field', $_POST['rtcl_tax_rate_state'] ?? [] );
		$postcodes    = array_map( 'sanitize_text_field', $_POST['rtcl_tax_rate_postcode'] ?? [] );
		$cities       = array_map( 'sanitize_text_field', $_POST['rtcl_tax_rate_city'] ?? [] );
		$rates        = array_map( 'floatval', $_POST['rtcl_tax_rate'] ?? [] );
		$tax_name     = array_map( 'sanitize_text_field', $_POST['rtcl_tax_rate_name'] ?? [] );
		$tax_priority = array_map( 'intval', $_POST['rtcl_tax_rate_priority'] ?? [] );

		$rows_to_insert = [];
		$param_types    = '%s, %s, %s, %s, %f, %s, %d';

		if ( ! empty( $countries ) ) {
			for ( $i = 0; $i < count( $countries ); $i ++ ) {
				$rows_to_insert[] = [
					$countries[ $i ],
					$states[ $i ] ?? '',
					$cities[ $i ] ?? '',
					$postcodes[ $i ] ?? '',
					$rates[ $i ],
					$tax_name[ $i ],
					$tax_priority[ $i ] ?? '1'
				];
			}
		}

		$table_name = $wpdb->prefix . 'rtcl_tax_rates';

		$query
			= "INSERT INTO {$table_name} (country, country_state, country_city, location_code, tax_rate, tax_rate_name, tax_rate_priority) VALUES ";


		$placeholders = array_fill( 0, count( $rows_to_insert ), "($param_types)" );
		$query        .= implode( ', ', $placeholders );

		$values = [];
		foreach ( $rows_to_insert as $row ) {
			$values = array_merge( $values, $row );
		}

		if ( ! empty( $rows_to_insert ) ) {
			$wpdb->query( "TRUNCATE TABLE $table_name" );
		}

		$prepared_query = $wpdb->prepare( $query, $values );

		$result = $wpdb->query( $prepared_query );

		if ( false === $result ) {
			$wpdb_error = $wpdb->last_error;
		}
	}
}
