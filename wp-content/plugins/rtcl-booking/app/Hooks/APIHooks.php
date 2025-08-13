<?php

namespace RtclBooking\Hooks;

use Rtcl\Helpers\Functions;
use RtclBooking\Resources\Options as BookingOptions;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RtclPro\Helpers\Api;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class APIHooks {

    /**
     * Member Variable
     *
     * @var instance
     */
    private static $instance;

    /**
     *  Initiator
     */
    public static function get_instance() {
        if ( !isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_filter( 'rtcl_rest_api_config_data', [ $this, 'booking_settings' ] );
        add_filter( 'rtcl_rest_listing_form_data', [ $this, 'booking_form_data' ], 10, 2 );
        add_filter( 'rtcl_rest_api_listing_data', [ $this, 'booking_data' ], 10, 2 );
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ], 100 );
    }

    /**
     * Register rest routes for booking
     *
     * @return void
     */
    public function register_rest_routes() {
        register_rest_route( 'rtcl/v1', '/booking/availability/(?P<listing_id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'check_booking_availability' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'listing_id'   => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Listing id is required', 'rtcl-booking' ),
                    ],
                    'no_of_ticket' => [
                        'required' => false,
                        'type'     => 'integer'
                    ],
                    'booking_date' => [
                        'required' => false,
                        'type'     => 'date'
                    ],
                    'time_slot'    => [
                        'required' => false,
                        'type'     => 'string'
                    ],
                ]
            ]
        ] );
        register_rest_route( 'rtcl/v1', '/booking/slots/(?P<listing_id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_available_service_slots' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'listing_id'   => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Listing id is required', 'rtcl-booking' ),
                    ],
                    'booking_date' => [
                        'required' => true,
                        'type'     => 'date'
                    ],
                ]
            ]
        ] );
        register_rest_route( 'rtcl/v1', '/booking/create', [
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'create_booking' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'listing_id' => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Listing id is required', 'rtcl-booking' ),
                    ],
                    'name'       => [
                        'required'    => true,
                        'type'        => 'string',
                        'description' => esc_html__( 'Name is required.', 'rtcl-booking' ),
                    ],
                    'email'      => [
                        'required'    => true,
                        'type'        => 'string',
                        'description' => esc_html__( 'Email is required.', 'rtcl-booking' ),
                    ],
                    'phone'      => [
                        'required'    => true,
                        'type'        => 'string',
                        'description' => esc_html__( 'Phone is required.', 'rtcl-booking' ),
                    ],
                    'fee'        => [
                        'required'    => true,
                        'type'        => 'string',
                        'description' => esc_html__( 'Booking fee is required.', 'rtcl-booking' ),
                    ]
                ]
            ]
        ] );
        register_rest_route( 'rtcl/v1', '/my/bookings', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_my_bookings' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'per_page' => [
                        'description'       => esc_html__( 'Maximum number of items to be returned in result set.', 'rtcl-booking' ),
                        'type'              => 'integer',
                        'default'           => 20,
                        'minimum'           => 1,
                        'maximum'           => 100,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ],
                    'page'     => [
                        'description'       => esc_html__( 'Current page of the collection.', 'rtcl-booking' ),
                        'type'              => 'integer',
                        'default'           => 1,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                        'minimum'           => 1,
                    ],
                    'status'   => [
                        'description'       => esc_html__( 'The filter parameter is used to filter the collection of status.', 'rtcl-booking' ),
                        'type'              => 'string',
                        'validate_callback' => 'rest_validate_request_arg',
                        'enum'              => [ 'approved', 'pending', 'canceled', 'rejected' ],
                    ]
                ]
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $this, 'delete_my_booking' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'booking_id' => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Booking id is required.', 'rtcl-booking' ),
                    ]
                ]
            ]
        ] );
        register_rest_route( 'rtcl/v1', '/my/booking/cancel/(?P<booking_id>[\d]+)', [
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $this, 'cancel_my_booking_callback' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'booking_id' => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Booking id is required.', 'rtcl-booking' ),
                    ]
                ]
            ]
        ] );
        register_rest_route( 'rtcl/v1', '/my/user-bookings', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_all_bookings' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'per_page' => [
                        'description'       => esc_html__( 'Maximum number of items to be returned in result set.', 'rtcl-booking' ),
                        'type'              => 'integer',
                        'default'           => 20,
                        'minimum'           => 1,
                        'maximum'           => 100,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ],
                    'page'     => [
                        'description'       => esc_html__( 'Current page of the collection.', 'rtcl-booking' ),
                        'type'              => 'integer',
                        'default'           => 1,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                        'minimum'           => 1,
                    ],
                    'status'   => [
                        'description'       => esc_html__( 'The filter parameter is used to filter the collection of status.', 'rtcl-booking' ),
                        'type'              => 'string',
                        'validate_callback' => 'rest_validate_request_arg',
                        'enum'              => [ 'approved', 'pending', 'canceled', 'rejected' ],
                    ]
                ]
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $this, 'user_booking_action_callback' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'booking_id' => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Booking id is required.', 'rtcl-booking' ),
                    ],
                    'action'     => [
                        'required'          => true,
                        'type'              => 'string',
                        'description'       => esc_html__( 'Please, specify a action.', 'rtcl-booking' ),
                        'validate_callback' => 'rest_validate_request_arg',
                        'enum'              => [ 'approved', 'canceled', 'rejected' ],
                    ]
                ]
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $this, 'delete_user_booking_callback' ],
                'permission_callback' => [ Api::class, 'permission_check' ],
                'args'                => [
                    'booking_id' => [
                        'required'    => true,
                        'type'        => 'integer',
                        'description' => esc_html__( 'Booking id is required.', 'rtcl-booking' ),
                    ]
                ]
            ]
        ] );
    }

    /**
     * Get my bookings
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_REST_Response
     */
    public function get_my_bookings( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $per_page = (int)$request->get_param( "per_page" );
        $page = (int)$request->get_param( "page" );
        $status = $request->get_param( "status" );

        $response = $this->get_my_booking_list( $user_id, $per_page, $page, $status );

        return rest_ensure_response( $response );
    }

    /**
     * Query data for booking list
     *
     * @param integer $user_id
     * @param integer $per_page
     * @param integer $page
     *
     * @return array
     */
    private function get_my_booking_list( $user_id, $per_page, $page, $status = null ) {
        global $wpdb;

        $booking_info_table = $wpdb->prefix . "rtcl_booking_info";
        $offset = ( $page * $per_page ) - $per_page;
        $where = '';
        if ( $status ) {
            $where = $wpdb->prepare( " AND status = %s", $status );
        }
        $query = $wpdb->prepare( "SELECT * FROM $booking_info_table WHERE user_id = %d$where", $user_id );
        $total_query = "SELECT COUNT(1) FROM ($query) AS combined_table";
        $total = $wpdb->get_var( $total_query );

        $results = $wpdb->get_results( $wpdb->prepare( "$query ORDER BY requested_at DESC LIMIT %d, %d", [
            esc_sql( $offset ),
            esc_sql( $per_page )
        ] ) );

        $data = [];
        if ( !empty( $results ) && is_array( $results ) ) {
            foreach ( $results as $result ) {
                $result->details = !empty( $result->details ) ? maybe_unserialize( $result->details ) : [];
                $listing = rtcl()->factory->get_listing( $result->listing_id );
                $result->listing = $listing ? Api::get_listing_data( $listing ) : [];
                $result->meta = BookingFunctions::get_booking_meta( $result->listing_id );
                if ( !empty( $result->meta['_rtcl_shs'] ) ) {
                    $result->meta['_rtcl_shs'] = maybe_unserialize( $result->meta['_rtcl_shs'] );
                }
                $data[] = $result;
            }
        }

        return [
            'data'       => $data,
            "pagination" => [
                "total"        => (int)$total,
                "count"        => count( $data ),
                "per_page"     => $per_page,
                "current_page" => $page,
                "total_pages"  => ceil( $total / $per_page )
            ]
        ];
    }

    /**
     * Get all bookings
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_REST_Response
     */
    public function get_all_bookings( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $per_page = (int)$request->get_param( "per_page" );
        $page = (int)$request->get_param( "page" );
        $status = $request->get_param( 'status' );

        $response = $this->get_all_booking_list( $user_id, $per_page, $page, $status );

        return rest_ensure_response( $response );
    }

    /**
     * Query data for booking list
     *
     * @param integer $user_id
     * @param integer $per_page
     * @param integer $page
     * @param string $status
     *
     * @return array
     */
    private function get_all_booking_list( $user_id, $per_page, $page, $status = '' ) {
        global $wpdb;

        $booking_info_table = $wpdb->prefix . "rtcl_booking_info";
        $offset = ( $page * $per_page ) - $per_page;
        $where = '';
        if ( $status ) {
            $where = $wpdb->prepare( " AND status = %s", $status );
        }
        $query = $wpdb->prepare( "SELECT * FROM $booking_info_table WHERE listing_owner_id = %d $where", $user_id );
        $total_query = "SELECT COUNT(1) FROM ($query) AS combined_table";
        $total = $wpdb->get_var( $total_query );

        $results = $wpdb->get_results( $wpdb->prepare( "$query ORDER BY requested_at DESC LIMIT %d, %d", [
            esc_sql( $offset ),
            esc_sql( $per_page )
        ] ) );

        $data = [];
        if ( !empty( $results ) && is_array( $results ) ) {
            foreach ( $results as $result ) {
                $result->details = !empty( $result->details ) ? maybe_unserialize( $result->details ) : [];
                $listing = rtcl()->factory->get_listing( $result->listing_id );
                $result->listing = $listing ? Api::get_listing_data( $listing ) : [];
                $result->meta = BookingFunctions::get_booking_meta( $result->listing_id );
                if ( !empty( $result->meta['_rtcl_shs'] ) ) {
                    $result->meta['_rtcl_shs'] = maybe_unserialize( $result->meta['_rtcl_shs'] );
                }
                $data[] = $result;
            }
        }

        return [
            'data'       => $data,
            "pagination" => [
                "total"        => (int)$total,
                "count"        => count( $data ),
                "per_page"     => $per_page,
                "current_page" => $page,
                "total_pages"  => ceil( $total / $per_page )
            ]
        ];
    }

    /**
     * Cancel a booking from my requested booking
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_REST_Response
     */
    public function cancel_my_booking_callback( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $booking_id = $request->get_param( 'booking_id' );

        $response = [
            'success' => false,
            'message' => ''
        ];

        if ( !$booking_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Booking id is required.'
            ];
            wp_send_json( $response, 400 );
        } else {
            global $wpdb;

            $booking_info_table = $wpdb->prefix . "rtcl_booking_info";

            $data = [
                'status'     => 'canceled',
                'updated_at' => current_time( 'mysql' )
            ];
            $where = [
                'id' => $booking_id
            ];

            $updated = $wpdb->update( $booking_info_table, $data, $where );

            if ( $updated ) {
                $response['success'] = true;
                $response['message'] = esc_html__( 'Cancelled booking successfully.', 'rtcl-booking' );
            } else {
                $response['message'] = esc_html__( 'Booking id not found!', 'rtcl-booking' );
            }
        }

        return rest_ensure_response( $response );
    }

    /**
     * Cancel a user requested booking
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_REST_Response
     */
    public function user_booking_action_callback( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $booking_id = (int)$request->get_param( 'booking_id' );
        $action = $request->get_param( 'action' );

        if ( !$booking_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Booking id is required.'
            ];
            wp_send_json( $response, 400 );
        }

        global $wpdb;

        $booking_info_table = $wpdb->prefix . "rtcl_booking_info";

        $bookingInfo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$booking_info_table} WHERE id = %d", $booking_id ) );
        if ( !$bookingInfo ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => 'No booking found to remove.'
            ];
            wp_send_json( $response, 403 );
        }

        if ( $user_id !== (int)$bookingInfo->listing_owner_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => 'You are not permitted to update booking.'
            ];
            wp_send_json( $response, 403 );
        }

        $response = [
            'success' => false
        ];
        $message = '';

        if ( 'canceled' === $action ) {
            $message = esc_html__( 'Cancelled booking successfully.', 'rtcl-booking' );
        } else if ( 'approved' === $action ) {
            $message = esc_html__( 'Approved booking successfully.', 'rtcl-booking' );
        } else if ( 'rejected' === $action ) {
            $message = esc_html__( 'Rejected booking successfully.', 'rtcl-booking' );
        }

        $data = [
            'status'     => $action,
            'updated_at' => current_time( 'mysql' )
        ];
        $where = [
            'id' => $booking_id
        ];

        $updated = $wpdb->update( $booking_info_table, $data, $where );

        if ( $updated ) {
            $response['success'] = true;
            $response['message'] = $message;
        } else {
            $response['message'] = esc_html__( 'Error while updating booking!', 'rtcl-booking' );
        }

        return rest_ensure_response( $response );
    }

    /**
     * Delete a user requested booking
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_REST_Response
     */
    public function delete_my_booking( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $booking_id = (int)$request->get_param( 'booking_id' );

        if ( !$booking_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Booking id is required.'
            ];
            wp_send_json( $response, 400 );
        }

        global $wpdb;

        $booking_info_table = $wpdb->prefix . "rtcl_booking_info";

        $bookingInfo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$booking_info_table} WHERE id = %d", $booking_id ) );
        if ( !$bookingInfo ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => 'No booking found to remove.'
            ];
            wp_send_json( $response, 403 );
        }

        if ( $user_id !== (int)$bookingInfo->user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => 'You are not permitted to update booking.'
            ];
            wp_send_json( $response, 403 );
        }

        $deleted = $wpdb->delete( $booking_info_table, [ 'id' => $booking_id ] );

        $response = [
            'success' => false
        ];

        if ( $deleted ) {
            $response['success'] = true;
            $response['message'] = esc_html__( 'Deleted booking successfully.', 'rtcl-booking' );
        } else {
            $response['message'] = esc_html__( 'Booking id not found!', 'rtcl-booking' );
        }

        return rest_ensure_response( $response );

    }

    /**
     * Delete a user requested booking
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_REST_Response
     */
    public function delete_user_booking_callback( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $booking_id = (int)$request->get_param( 'booking_id' );

        if ( !$booking_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Booking id is required.'
            ];
            wp_send_json( $response, 400 );
        }

        global $wpdb;

        $booking_info_table = $wpdb->prefix . "rtcl_booking_info";

        $bookingInfo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$booking_info_table} WHERE id = %d", $booking_id ) );
        if ( !$bookingInfo ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => 'No booking found to remove.'
            ];
            wp_send_json( $response, 403 );
        }

        if ( $user_id !== (int)$bookingInfo->listing_owner_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => 'You are not permitted to update booking.'
            ];
            wp_send_json( $response, 403 );
        }

        $deleted = $wpdb->delete( $booking_info_table, [ 'id' => $booking_id ] );


        $response = [
            'success' => false
        ];

        if ( $deleted ) {
            $response['success'] = true;
            $response['message'] = esc_html__( 'Deleted booking successfully.', 'rtcl-booking' );
        } else {
            $response['message'] = esc_html__( 'Booking id not found!', 'rtcl-booking' );
        }

        return rest_ensure_response( $response );

    }

    /**
     * Create booking for a listing
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error | WP_REST_Response
     */
    public function create_booking( WP_REST_Request $request ) {
        Api::is_valid_auth_request();

        $user_id = get_current_user_id();

        if ( !$user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'FORBIDDEN',
                'code'          => '403',
                'error_message' => "You are not logged in."
            ];
            wp_send_json( $response, 403 );
        }

        $listing_id = absint( $request->get_param( 'listing_id' ) );

        if ( !$listing_id || ( !$listing = rtcl()->factory->get_listing( $listing_id ) )
            || $listing->get_post_type() !== rtcl()->post_type
        ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => esc_html__( 'Listing not found.', "rtcl-booking" )
            ];
            wp_send_json( $response, 400 );
        }

        $listing_owner_id = $listing->get_owner_id();

        if ( $listing_owner_id === $user_id ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'You are not permitted to booking.'
            ];
            wp_send_json( $response, 400 );
        }

        $name = sanitize_text_field( $request->get_param( 'name' ) );
        $email = sanitize_email( $request->get_param( 'email' ) );
        $phone = sanitize_text_field( $request->get_param( 'phone' ) );

        if ( empty( $name ) ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Name is required.'
            ];
            wp_send_json( $response, 400 );
        }

        if ( empty( $email ) ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Email is required.'
            ];
            wp_send_json( $response, 400 );
        }

        if ( empty( $phone ) ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => 'Phone is required.'
            ];
            wp_send_json( $response, 400 );
        }

        global $wpdb;

        $msg = '';
        $success = false;

        $message = stripslashes( esc_textarea( $request->get_param( "message" ) ) );
        $fee = sanitize_text_field( $request->get_param( "fee" ) );
        $persons = sanitize_text_field( $request->get_param( "ticket_no" ) );
        $booking_date = sanitize_text_field( $request->get_param( "booking_date" ) );
        $time_slot = sanitize_text_field( $request->get_param( "time_slot" ) );

        $details = [
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'message' => $message
        ];

        $booking_info_table = $wpdb->prefix . "rtcl_booking_info";

        $instant_booking = (bool)BookingFunctions::get_booking_meta( $listing_id, '_rtcl_instant_booking' );
        $booking_type = BookingFunctions::get_booking_meta( $listing_id, 'rtcl_listing_booking_type' );
        $booking_status = $instant_booking ? 'approved' : 'pending';

        if ( 'services' === $booking_type ) {
            if ( empty( $booking_date ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => 'Date is required.'
                ];
                wp_send_json( $response, 400 );
            }
            if ( empty( $time_slot ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => 'Service time is required.'
                ];
                wp_send_json( $response, 400 );
            }
        } else if ( 'pre_order' === $booking_type || 'event' === $booking_type ) {
            if ( empty( $persons ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => 'No of ticket is required.'
                ];
                wp_send_json( $response, 400 );
            }
        } else if ( 'rent' === $booking_type ) {
            if ( empty( $persons ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => 'No of ticket is required.'
                ];
                wp_send_json( $response, 400 );
            }
            if ( empty( $booking_date ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => 'Date is required.'
                ];
                wp_send_json( $response, 400 );
            }
        }

        $data = [
            'listing_id'       => $listing_id,
            'user_id'          => $user_id,
            'listing_owner_id' => $listing_owner_id,
            'quantity'         => $persons,
            'price'            => $fee,
            'details'          => serialize( $details ),
            'booking_date'     => $booking_date,
            'time_slot'        => $time_slot,
            'requested_at'     => current_time( 'mysql' ),
            'status'           => $booking_status,
        ];

        $wpdb->insert( $booking_info_table, $data );

        if ( $wpdb->insert_id ) {
            Functions::add_notice( apply_filters( 'rtcl_booking_success_message', esc_html__( "Thank you for submitting booking request!", "rtcl-booking" ),
                $_REQUEST ) );
            $success = true;

            $transient_key = 'rtcl_rent_booked_dates_' . $booking_status . '_' . $listing_id;
            delete_transient( $transient_key );
            // send email to listing owner
            rtcl()->mailer()->emails['Booking_Request_Email']->trigger( $listing_id, [ 'name' => $name ] );
            // send approve email to user when enable instant booking
            if ( $instant_booking ) {
                rtcl()->mailer()->emails['Booking_Approved_Email']->trigger( $wpdb->insert_id, [ 'listing_id' => $listing_id ] );
            }
        }

        $msg = Functions::get_notices( 'error' );
        if ( $success ) {
            $msg = Functions::get_notices( 'success' );
        }
        Functions::clear_notices(); // Clear all notice created by checking

        $response = [
            'success' => $success,
            'message' => $msg
        ];

        return rest_ensure_response( $response );

    }

    /**
     * Get available service slots
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error | WP_REST_Response
     */
    public function get_available_service_slots( WP_REST_Request $request ) {

        if ( !$request->get_param( 'listing_id' ) || ( !$listing = rtcl()->factory->get_listing( $request->get_param( 'listing_id' ) ) )
            || $listing->get_post_type() !== rtcl()->post_type
        ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => esc_html__( 'Listing not found.', "rtcl-booking" )
            ];
            wp_send_json( $response, 400 );
        }

        if ( !$request->get_param( 'booking_date' ) || empty( $request->get_param( 'booking_date' ) ) ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => esc_html__( 'Date is required.', "rtcl-booking" )
            ];
            wp_send_json( $response, 400 );
        }

        $listing_id = $request->get_param( 'listing_id' );
        $booking_type = BookingFunctions::get_booking_meta( $listing_id, 'rtcl_listing_booking_type' );

        if ( 'services' !== $booking_type ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => esc_html__( 'It\'s not service related booking.', "rtcl-booking" )
            ];
            wp_send_json( $response, 400 );
        }

        $booking_date = $request->get_param( 'booking_date' );
        $serviceHours = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_shs' );
        $serviceHours = maybe_unserialize( $serviceHours );

        $day = date( 'D', strtotime( $booking_date ) );
        $slots = [];

        if ( !empty( $serviceHours ) ) {
            $day = strtolower( $day );
            $slots = $serviceHours[BookingFunctions::get_day_index( $day )];
        }

        return rest_ensure_response( $slots );

    }


    /**
     * Booking form verification
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error | WP_REST_Response
     */
    public function check_booking_availability( WP_REST_Request $request ) {
        if ( !$request->get_param( 'listing_id' ) || ( !$listing = rtcl()->factory->get_listing( $request->get_param( 'listing_id' ) ) )
            || $listing->get_post_type() !== rtcl()->post_type
        ) {
            $response = [
                'status'        => "error",
                'error'         => 'BADREQUEST',
                'code'          => '400',
                'error_message' => esc_html__( 'Listing not found.', "rtcl-booking" )
            ];
            wp_send_json( $response, 400 );
        }

        $response = [
            'error'   => false,
            'message' => ''
        ];

        $listing_id = $request->get_param( 'listing_id' );
        $booking_type = BookingFunctions::get_booking_meta( $listing_id, 'rtcl_listing_booking_type' );

        if ( 'event' === $booking_type ) {

            if ( !$request->get_param( 'no_of_ticket' ) || empty( $request->get_param( 'no_of_ticket' ) ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => esc_html__( 'Number of ticket is required.', "rtcl-booking" )
                ];
                wp_send_json( $response, 400 );
            }

            $no_of_ticket = ( int )$request->get_param( 'no_of_ticket' );

            $response = BookingFunctions::check_event_type_availability( $listing_id, $no_of_ticket );

        } else if ( 'services' === $booking_type ) {

            if ( !$request->get_param( 'booking_date' ) || empty( $request->get_param( 'booking_date' ) ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => esc_html__( 'Date is required.', "rtcl-booking" )
                ];
                wp_send_json( $response, 400 );
            }

            if ( !$request->get_param( 'time_slot' ) || empty( $request->get_param( 'time_slot' ) ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => apply_filters( 'rtcl_booking_time_slot_empty_message', esc_html__( 'Please, select a time slot.', 'rtcl-booking' ) )
                ];
                wp_send_json( $response, 400 );
            }

            if ( !$request->get_param( 'no_of_ticket' ) || empty( $request->get_param( 'no_of_ticket' ) ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => esc_html__( 'Please, select number of guest.', 'rtcl-booking' )
                ];
                wp_send_json( $response, 400 );
            }

            $booking_date = $request->get_param( 'booking_date' );
            $time_slot = $request->get_param( 'time_slot' );
            $no_of_ticket = ( int )$request->get_param( 'no_of_ticket' );

            $response = BookingFunctions::check_services_type_availability( $listing_id, $booking_date, $time_slot, $no_of_ticket );

        } else if ( 'pre_order' === $booking_type ) {

            if ( !$request->get_param( 'no_of_ticket' ) || empty( $request->get_param( 'no_of_ticket' ) ) ) {
                $response = [
                    'status'        => "error",
                    'error'         => 'BADREQUEST',
                    'code'          => '400',
                    'error_message' => esc_html__( 'Please, select number of item.', 'rtcl-booking' )
                ];
                wp_send_json( $response, 400 );
            }

            $no_of_ticket = ( int )$request->get_param( 'no_of_ticket' );

            $response = BookingFunctions::check_pre_order_type_availability( $listing_id, $no_of_ticket );
        }

        return rest_ensure_response( $response );
    }

    /**
     * set booking settings data
     *
     * @param array $config configuration data
     *
     * @return array()
     */
    public function booking_settings( $config ) {
        $config['booking'] = [
            'booking_fee_label'     => apply_filters( 'rtcl_booking_fee_label', BookingFunctions::get_booking_fee_label() ),
            'instant_booking_label' => apply_filters( 'rtcl_booking_instant_booking_label', BookingFunctions::get_instant_booking_label() ),
            'booking_button_text'   => apply_filters( 'rtcl_booking_order_buton_text', BookingFunctions::get_booking_button_text() ),
            'event'                 => [
                'disable'                   => BookingFunctions::is_disable_booking_event_type(),
                'available_ticket_label'    => apply_filters( 'rtcl_booking_get_available_ticket_label', BookingFunctions::get_available_ticket_label() ),
                'allowed_per_booking_label' => apply_filters( 'rtcl_booking_ticket_allowed_per_booking_label',
                    BookingFunctions::get_ticket_allowed_per_booking_label() ),
            ],
            'services'              => [
                'disable'         => BookingFunctions::is_disable_booking_service_type(),
                'max_guest_label' => apply_filters( 'rtcl_booking_max_no_of_guest_label', BookingFunctions::get_maximum_number_of_guest_label() ),
                'schedule_label'  => apply_filters( 'rtcl_booking_schedule_label', BookingFunctions::get_service_schedule_label() ),
            ],
            'rent'                  => [
                'disable' => BookingFunctions::is_disable_booking_rent_type(),
            ],
            'pre_order'             => [
                'disable'                    => BookingFunctions::is_disable_booking_pre_order_type(),
                'pre_order_date_label'       => apply_filters( 'rtcl_booking_pre_order_date_label', BookingFunctions::get_pre_order_date_label() ),
                'pre_order_available_date'   => apply_filters( 'rtcl_booking_pre_order_available_date_label',
                    BookingFunctions::get_pre_order_availalbe_date_label() ),
                'pre_order_available_volumn' => BookingFunctions::get_pre_order_availalbe_volumn_label(),
                'allowed_per_order_label'    => apply_filters( 'rtcl_booking_pre_order_maximum_label', BookingFunctions::get_pre_order_per_order_label() ),
            ],
        ];

        return $config;
    }

    /**
     * Listing form fields for booking
     *
     * @param array $form_data available fields
     * @param object $listing
     *
     * @return array
     */
    public function booking_form_data( $form_data, $listing ) {

        $form_data['config']['booking'] = [
            'active'          => [
                'type'  => 'checkbox',
                'name'  => '_rtcl_booking_active',
                'label' => apply_filters( 'rtcl_booking_active_label', __( 'Activate Booking', 'rtcl-booking' ) )
            ],
            'type'            => [
                'type'    => 'radio',
                'name'    => '_rtcl_listing_booking',
                'label'   => apply_filters( 'rtcl_booking_type_label', __( 'Booking Type', 'rtcl-booking' ) ),
                'options' => BookingOptions::get_listing_booking_types(),
            ],
            'fee'             => [
                'type'  => 'number',
                'name'  => '_rtcl_booking_fee',
                'label' => apply_filters( 'rtcl_booking_fee_label', BookingFunctions::get_booking_fee_label() )
            ],
            'instant_booking' => [
                'type'  => 'checkbox',
                'name'  => '_rtcl_instant_booking',
                'label' => apply_filters( 'rtcl_booking_instant_booking_label', BookingFunctions::get_instant_booking_label() )
            ],
            'event'           => [
                'display_available_tickets' => [
                    'type'  => 'checkbox',
                    'name'  => '_rtcl_show_available_tickets',
                    'label' => apply_filters( 'rtcl_booking_display_available_ticket_label', __( 'Display Available Ticket', 'rtcl-booking' ) )
                ],
                'available_tickets'         => [
                    'type'  => 'number',
                    'name'  => '_rtcl_available_tickets',
                    'label' => apply_filters( 'rtcl_booking_get_available_ticket_label', BookingFunctions::get_available_ticket_label() )
                ],
                'allowed_per_booking'       => [
                    'type'  => 'number',
                    'name'  => '_rtcl_booking_allowed_ticket',
                    'label' => apply_filters( 'rtcl_booking_ticket_allowed_per_booking_label', BookingFunctions::get_ticket_allowed_per_booking_label() )
                ],
            ],
            'services'        => [
                'max_guest' => [
                    'type'  => 'number',
                    'name'  => '_rtcl_booking_max_guest',
                    'label' => apply_filters( 'rtcl_booking_max_no_of_guest_label', BookingFunctions::get_maximum_number_of_guest_label() )
                ],
                'schedule'  => [
                    'type'  => 'custom',
                    'name'  => '_rtcl_shs',
                    'label' => apply_filters( 'rtcl_booking_schedule_label', BookingFunctions::get_service_schedule_label() )
                ],
            ],
            'rent'            => [
                'max_guest'    => [
                    'type'  => 'number',
                    'name'  => '_rtcl_booking_rent_max_guest',
                    'label' => apply_filters( 'rtcl_booking_max_no_of_guest_label', BookingFunctions::get_maximum_number_of_guest_label() )
                ],
                'disable_date' => [
                    'type'  => 'calender',
                    'name'  => '_rtcl_booking_disable_date',
                    'label' => apply_filters( 'rtcl_booking_rent_date_label', __( 'Unavailable Date', 'rtcl-booking' ) )
                ],
            ],
            'pre_order'       => [
                'pre_order_date'   => [
                    'type'  => 'date_range',
                    'name'  => '_rtcl_booking_pre_order_date',
                    'label' => apply_filters( 'rtcl_booking_pre_order_date_label', BookingFunctions::get_pre_order_date_label() )
                ],
                'available_from'   => [
                    'type'  => 'date',
                    'name'  => '_rtcl_booking_pre_order_available_date',
                    'label' => apply_filters( 'rtcl_booking_pre_order_available_date_label', BookingFunctions::get_pre_order_availalbe_date_label() ),
                ],
                'available_volumn' => [
                    'type'  => 'number',
                    'name'  => '_rtcl_booking_pre_order_available_volumn',
                    'label' => BookingFunctions::get_pre_order_availalbe_volumn_label(),
                ],
                'max_order'        => [
                    'type'  => 'number',
                    'name'  => '_rtcl_booking_pre_order_maximum',
                    'label' => apply_filters( 'rtcl_booking_pre_order_maximum_label', BookingFunctions::get_pre_order_per_order_label() ),
                ],
            ],
        ];

        return $form_data;
    }

    /**
     * Single listing booking data
     *
     * @param array $data
     * @param object $listing
     *
     * @return array
     */
    public function booking_data( $data, $listing ) {

        if ( $listing ) {
            $listing_id = $listing->get_id();

            $booking_type = BookingFunctions::get_booking_type( $listing_id );

            $data['booking'] = [
                '_rtcl_booking_active'  => BookingFunctions::is_active_booking( $listing_id ),
                '_rtcl_listing_booking' => $booking_type,
                '_rtcl_booking_fee'     => BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_fee' ),
                '_rtcl_instant_booking' => BookingFunctions::get_booking_meta( $listing_id, '_rtcl_instant_booking' ),
            ];

            if ( 'services' === $booking_type ) {
                $shs = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_shs' );
                $shs = !empty( $shs ) && is_serialized( $shs ) ? unserialize( $shs ) : [];

                $data['booking']['_rtcl_booking_max_guest'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_max_guest' );
                $data['booking']['_rtcl_shs'] = $shs;
            } else if ( 'pre_order' === $booking_type ) {
                $data['booking']['booked_tickets'] = BookingFunctions::get_booked_ticket( $listing_id );
                $data['booking']['_rtcl_booking_pre_order_date'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_date' );
                $data['booking']['_rtcl_booking_pre_order_available_date'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_available_date' );
                $data['booking']['_rtcl_booking_pre_order_available_volumn'] = absint( BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_available_volumn' ) );
                $data['booking']['_rtcl_booking_pre_order_maximum'] = absint( BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_maximum' ) );
            } else if ( 'rent' === $booking_type ) {
                $unavailable_date = maybe_unserialize( BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_rent_unavailable_date' ) );

                $data['booking']['_rtcl_booking_rent_max_guest'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_max_guest' );
                $data['booking']['_rtcl_booking_disable_date'] = is_array( $unavailable_date ) && !empty($unavailable_date) ? array_filter( $unavailable_date ) : '';
            } else {
                $data['booking']['_rtcl_show_available_tickets'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_show_available_tickets' );
                $data['booking']['_rtcl_available_tickets'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_available_tickets' );
                $data['booking']['booked_tickets'] = BookingFunctions::get_booked_ticket( $listing_id );
                $data['booking']['_rtcl_booking_allowed_ticket'] = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_allowed_ticket' );
            }

        }

        return $data;
    }

}