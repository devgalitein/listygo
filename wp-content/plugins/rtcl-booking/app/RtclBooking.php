<?php

require_once RTCL_BOOKING_PATH . 'vendor/autoload.php';

use Rtcl\Helpers\Functions as RtclFunctions;
use Rtcl\Services\FormBuilder\FBHelper;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RtclBooking\Helpers\Installer;
use RtclBooking\Admin\AdminHooks;
use RtclBooking\Hooks\AjaxHooks;
use RtclBooking\Hooks\APIHooks as BookingAPI;
use RtclBooking\Hooks\PaymentHooks;
use RtclBooking\Models\Dependencies;
use RtclBooking\Hooks\ActionHooks;
use RtclBooking\Hooks\FilterHooks;
use Rtcl\Helpers\Utility;

final class RtclBooking {

	private $suffix;
	private $version;

	/**
	 * Booking the singleton object.
	 */
	private static $singleton = false;

	/**
	 * Create an inaccessible constructor.
	 */
	private function __construct() {
		$this->suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : RTCL_BOOKING_VERSION;

		$this->load_scripts();
		$this->init();
	}

	/**
	 * Fetch an instance of the class.
	 */
	final public static function getInstance() {
		if ( self::$singleton === false ) {
			self::$singleton = new self();
		}

		return self::$singleton;
	}

	/**
	 * Classified Listing Constructor.
	 */
	protected function init() {
		$this->define_constants();
		$this->load_language();
		$this->hooks();
	}

	private function load_scripts() {
		$dependence = Dependencies::getInstance();
		if ( $dependence->check() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'front_end_script' ], 30 );
			add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_script' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'load_settings_script' ] );
		}
	}

	private function define_constants() {
		if ( ! defined( 'RTCL_BOOKING_URL' ) ) {
			define( 'RTCL_BOOKING_URL', plugins_url( '', RTCL_BOOKING_PLUGIN_FILE ) );
		}
		if ( ! defined( 'RTCL_BOOKING_SLUG' ) ) {
			define( 'RTCL_BOOKING_SLUG', basename( dirname( RTCL_BOOKING_PLUGIN_FILE ) ) );
		}
		if ( ! defined( 'RTCL_BOOKING_PLUGIN_DIRNAME' ) ) {
			define( 'RTCL_BOOKING_PLUGIN_DIRNAME', dirname( plugin_basename( RTCL_BOOKING_PLUGIN_FILE ) ) );
		}
		if ( ! defined( 'RTCL_BOOKING_PLUGIN_BASENAME' ) ) {
			define( 'RTCL_BOOKING_PLUGIN_BASENAME', plugin_basename( RTCL_BOOKING_PLUGIN_FILE ) );
		}
	}

	public function load_language() {
		load_plugin_textdomain( 'rtcl-booking', false, trailingslashit( RTCL_BOOKING_PLUGIN_DIRNAME ) . 'languages' );
	}

	private function hooks() {
		$dependence = Dependencies::getInstance();
		if ( $dependence->check() ) {
			FilterHooks::init();
			ActionHooks::init();
			AjaxHooks::init();
			if ( is_admin() ) {
				AdminHooks::init();
			}
			if ( BookingFunctions::is_enable_booking_payment() ) {
				PaymentHooks::init();
			}
			if ( class_exists( 'RtclPro' ) && BookingFunctions::is_enable_booking() ) {
				BookingAPI::get_instance();
			}
			do_action( 'rtcl_booking_loaded', $this );
		}
	}

	public function front_end_script() {

		wp_register_script( 'fullcalendar', RTCL_BOOKING_URL . "/assets/js/index.global{$this->suffix}.js", '', $this->version, true );
		wp_register_script( 'rtcl-booking', RTCL_BOOKING_URL . "/assets/js/booking{$this->suffix}.js", [
			'rtcl-common',
			'rtcl-validator',
			'rtcl-public',
			'fullcalendar'
		], $this->version, true );

		wp_register_style( 'rtcl-booking', RTCL_BOOKING_URL . '/assets/css/booking.css', [
			'rtcl-public'
		], $this->version );

		if ( RtclFunctions::is_account_page() || RtclFunctions::is_listing_form_page() || RtclFunctions::is_listing() ) {
			wp_enqueue_style( 'rtcl-booking' );
		}

		$service_hours_localize = apply_filters( 'rtcl_booking_service_hours_localize_options', [
			'ajax_url'      => admin_url( "admin-ajax.php" ),
			'approve_text'  => esc_html__( 'Approved', 'rtcl-booking' ),
			'cancel_text'   => esc_html__( 'Cancel', 'rtcl-booking' ),
			'delete_text'   => esc_html__( 'Delete', 'rtcl-booking' ),
			'reject_text'   => esc_html__( 'Rejected', 'rtcl-booking' ),
			'canceled_text' => esc_html__( 'Canceled', 'rtcl-booking' ),
			rtcl()->nonceId => wp_create_nonce( rtcl()->nonceText ),
			'rent_calendar' => [
				'available_text' => esc_html__( 'Available', 'rtcl-booking' ),
				'booked_text'    => esc_html__( 'Booked', 'rtcl-booking' ),
				'pending_text'   => esc_html__( 'Pending', 'rtcl-booking' ),
				'past_text'      => esc_html__( 'Past', 'rtcl-booking' ),
				'active_text'    => esc_html__( 'Selected', 'rtcl-booking' )
			],
			"lang"          => [
				'server_error' => esc_html__( "Server Error!!", "rtcl-booking" ),
				'confirm'      => esc_html__( "Are you sure to delete?", "rtcl-booking" ),
			],
			'timePicker'    => [
				'startDate' => '09:00 AM',
				'locale'    => [
					"format"      => Utility::dateFormatPHPToMoment( RtclFunctions::time_format() ),
					"applyLabel"  => esc_html__( "Apply", "rtcl-booking" ),
					"cancelLabel" => esc_html__( "Clear", "rtcl-booking" )
				]
			],
			'timePickerEnd' => [
				'startDate' => '05:00 PM',
			],
			'locale'        => get_bloginfo( 'language' )
		] );

		wp_localize_script( 'rtcl-booking', 'rtcl_booking', $service_hours_localize );

		if ( RtclFunctions::is_listing_form_page() || RtclFunctions::is_listing() || RtclFunctions::is_account_page() ) {
			wp_enqueue_script( 'fullcalendar' );
			wp_enqueue_script( 'rtcl-booking' );
		}
	}

	public function load_settings_script() {

		if ( isset( $_GET['page'] ) && 'rtcl-settings' === $_GET['page'] ) {
			wp_enqueue_style( 'rtcl-booking-admin', RTCL_BOOKING_URL . '/assets/css/booking-admin.css', [
				'rtcl-admin'
			], $this->version );
		}
	}

	public function load_admin_script() {
		global $pagenow, $post_type;

		if ( rtcl()->post_type != $post_type || FBHelper::isEnabled() ) {
			return;
		}

		wp_enqueue_style( 'rtcl-booking-admin', RTCL_BOOKING_URL . '/assets/css/booking-admin.css', [
			'rtcl-admin'
		], $this->version );

		wp_register_script( 'fullcalendar', RTCL_BOOKING_URL . "/assets/js/index.global{$this->suffix}.js", '', $this->version, true );
		wp_register_script( 'rtcl-booking-admin', RTCL_BOOKING_URL . "/assets/js/booking-admin{$this->suffix}.js", [
			'rtcl-admin',
			'jquery',
			'fullcalendar'
		], $this->version, true );

		$service_hours_localize = apply_filters( 'rtcl_booking_service_hours_localize_options', [
			'timePicker'    => [
				'startDate' => '09:00 AM',
				'locale'    => [
					"format"      => Utility::dateFormatPHPToMoment( RtclFunctions::time_format() ),
					"applyLabel"  => esc_html__( "Apply", "rtcl-booking" ),
					"cancelLabel" => esc_html__( "Clear", "rtcl-booking" )
				]
			],
			'timePickerEnd' => [
				'startDate' => '05:00 PM',
			],
			'locale'        => get_bloginfo( 'language' )
		] );

		wp_localize_script( 'rtcl-booking-admin', 'rtcl_booking', $service_hours_localize );
		wp_enqueue_script( 'fullcalendar' );
		wp_enqueue_script( 'rtcl-booking-admin' );
	}

	/**
	 * @return string
	 */
	public function get_plugin_template_path() {
		return $this->plugin_path() . '/templates/';
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( RTCL_BOOKING_PLUGIN_FILE ) );
	}

}

/**
 * @return RtclBooking
 */
function rtclBooking() {
	return RtclBooking::getInstance();
}

rtclBooking();

register_activation_hook( RTCL_BOOKING_PLUGIN_FILE, [ Installer::class, 'activate' ] );
register_deactivation_hook( RTCL_BOOKING_PLUGIN_FILE, [ Installer::class, 'deactivate' ] );