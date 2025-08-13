<?php
/**
 * Created by PhpStorm.
 * User: mahbubur
 * Date: 7/24/18
 * Time: 12:10 PM
 */

namespace RtclStore\Controllers\Ajax;


use Rtcl\Helpers\Functions;

class ApiRequest {

    public static function init() {
        add_action( 'wp_ajax_rtcl_get_all_membership_list', [ __CLASS__, 'rtcl_get_all_membership_list' ] );
        add_action( 'wp_ajax_rtcl_delete_membership', [ __CLASS__, 'rtcl_delete_membership' ] );
        add_action( 'wp_ajax_rtcl_update_membership_data', [ __CLASS__, 'rtcl_update_membership_data' ] );
    }

    public static function rtcl_update_membership_data() {

        if ( !Functions::verify_nonce() ) {
            wp_send_json_error( __( "Session not valid", "classified-listing-store" ) );
        }

        if ( !empty( $_POST['data'] ) && !is_array( $_POST['data'] ) ) {
            wp_send_json_error( __( "No data found to update", "classified-listing-store" ) );
        }

        $id = !empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        $membership = rtclStore()->factory->get_membership_by_id( $id );
        if ( !$membership ) {
            wp_send_json_error( __( "No membership found to update", "classified-listing-store" ) );
        }

        $data = $membership->update_membership_data( $_POST['data'] );

        if ( is_wp_error( $data ) ) {
            wp_send_json_error( $data->get_error_message() );
        }

        wp_send_json_success( [
            'message' => __( "Update successfully", "classified-listing-store" ),
            'data'    => $data
        ] );
    }

    public static function rtcl_delete_membership() {
        if ( !Functions::verify_nonce() ) {
            wp_send_json_error( __( "Session not valid", "classified-listing-store" ) );
        }

        $ready_ids = !empty( $_POST['ids'] ) && is_array( $_POST['ids'] ) ? $_POST['ids'] : [];
        if ( empty( $ready_ids ) ) {
            wp_send_json_error( __( "Please select a row to delete", "classified-listing-store" ) );
        }
        $ids = [];

        global $wpdb;
        foreach ( $ready_ids as $id ) {
            if ( $wpdb->delete( $wpdb->prefix . 'rtcl_membership', [ 'id' => $id ], [ '%d' ] ) ) {
                $ids[] = $id;
            }
        }

        if ( empty( $ids ) ) {
            wp_send_json_error( __( "No membership removed", "classified-listing-store" ) );
        }

        wp_send_json_success( [ 'message' => __( "Membership removed successfully", "classified-listing-store" ), 'ids' => $ids ] );

    }

    static function rtcl_get_all_membership_list() {
        if ( !Functions::verify_nonce() ) {
            wp_send_json_error( __( "Session not valid", "classified-listing-store" ) );
        }

        $filters = !empty( $_POST['filters'] ) && is_array( $_POST['filters'] ) ? array_filter( $_POST['filters'] ) : [];

        $data = self::get_membership_list( $filters );
        wp_send_json_success( $data );
    }

    private static function get_membership_list( $filters ) {

        global $wpdb;
        $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
        $where = '';
        if ( !empty( $filters['id'] ) && $id = absint( $filters['id'] ) ) {
            $where .= ' AND m.id = ' . $id;
        }
        if ( !empty( $filters['user_id'] ) && $user_id = absint( $filters['user_id'] ) ) {
            $where .= ' AND m.user_id = ' . $user_id;
        }
        if ( !empty( $filters['email'] ) && $email = sanitize_text_field( trim( $filters['email'] ) ) ) {
            $where .= $wpdb->prepare( ' AND u.user_email LIKE %s', "%" . $email . "%" );
        }
        if ( !empty( $filters['user_name'] ) && $user_name = sanitize_text_field( trim( $filters['user_name'] ) ) ) {
            $where .= $wpdb->prepare( ' AND u.user_login LIKE %s', "%" . $user_name . "%" );
        }

        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}rtcl_membership m, {$prefix}users u WHERE m.user_id = u.ID {$where}";
        $total = (int)$wpdb->get_var( $query );

        $per_page = 20;
        $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
        $page = !$page ? 1 : $page;
        $offset = ( $page * $per_page ) - $per_page;

        $members = $wpdb->get_results(
            "SELECT m.*, u.user_email as email , u.user_login as user_name, u.ID as user_id 
					  FROM {$wpdb->prefix}rtcl_membership m, {$prefix}users u
					  WHERE m.user_id = u.ID {$where}
					  ORDER BY m.id DESC
					LIMIT $offset, $per_page" );

        return [
            'members'    => $members,
            'pagination' => [
                'current'  => $page,
                'per_page' => $per_page,
                'total'    => $total
            ]
        ];
    }

}