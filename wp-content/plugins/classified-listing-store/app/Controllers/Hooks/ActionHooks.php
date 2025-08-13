<?php

namespace RtclStore\Controllers\Hooks;

use Rtcl\Controllers\Settings\AdminSettings;
use WP_Error;

class ActionHooks {
    public static function init() {
        add_action( 'rtcl_store_contact_form_validation', [ __CLASS__, 'store_contact_form_validation' ], 10, 2 );
        add_action( 'delete_user', [ __CLASS__, 'remove_membership_data' ] );
        add_action( 'before_delete_post', [ __CLASS__, 'update_posting_count' ] );
        add_action( 'wp', [ __CLASS__, 'migrate_store_business_hour' ] );
        add_action( 'rtcl_admin_settings_saved', [ __CLASS__, 'update_pay_per_ad_settings' ], 10, 2 );
    }

    /**
     * @param $option
     * @param AdminSettings $SettingsModal
     * @return void
     * if pay_per_ad is enable then forcefully enable free ads and update number_of_free_ads to 999
     */
    public static function update_pay_per_ad_settings( $option, AdminSettings $SettingsModal ) {
        if ( $option !== 'membership_settings' ) {
            return;
        }
        $settings = get_option( 'rtcl_membership_settings', [] );
        if ( !empty( $settings['enable_pay_per_ad'] ) && $settings['enable_pay_per_ad'] === 'yes' ) {
            if ( ( empty( $settings['enable_free_ads'] ) || empty( $settings['number_of_free_ads'] ) ) || ( $settings['enable_free_ads'] !== 'yes' || $settings['number_of_free_ads'] !== 999 ) ) {
                $settings['enable_free_ads'] = 'yes';
                $settings['number_of_free_ads'] = 999;
                update_option( 'rtcl_membership_settings', $settings );
            }
        }
    }

    public static function migrate_store_business_hour() {
        $migrated = get_option( 'rtcl_store_migrated_business_hour' );
        if ( 'yes' !== $migrated ) {
            $stores = get_posts( [ 'post_type' => 'store', 'fields' => 'ids', 'posts_per_page' => -1 ] );
            $data = [];
            if ( !empty( $stores ) ) {
                foreach ( $stores as $store_id ) {
                    $store = rtclStore()->factory->get_store( $store_id );
                    if ( $store ) {
                        $hours = get_post_meta( $store->get_id(), 'oh_hours', true );
                        if ( !empty( $hours ) ) {
                            foreach ( $hours as $key => $hour ) {
                                switch ( $key ) {
                                    case 'sunday':
                                        $key = 0;
                                        break;
                                    case 'monday':
                                        $key = 1;
                                        break;
                                    case 'tuesday':
                                        $key = 2;
                                        break;
                                    case 'wednesday':
                                        $key = 3;
                                        break;
                                    case 'thursday':
                                        $key = 4;
                                        break;
                                    case 'friday':
                                        $key = 5;
                                        break;
                                    case 'saturday':
                                        $key = 6;
                                        break;
                                }
                                $data[$key] = $hour;
                            }
                        }
                        if ( !empty( $data ) ) {
                            update_post_meta( $store->get_id(), 'oh_hours', $data );
                        }
                    }
                }
                add_option( 'rtcl_store_migrated_business_hour', 'yes' );
            }
        }
    }

    public static function update_posting_count( $post_id ) {

        $listing = rtcl()->factory->get_listing( $post_id );

        $permitted_status = apply_filters( 'rtcl_listing_log_permitted_status', [ 'rtcl-reviewed', 'draft', 'pending', 'trash' ] );
        if ( $listing && in_array( $listing->get_status(), $permitted_status ) ) {
            $publish_count = absint( get_post_meta( $listing->get_id(), '_rtcl_publish_count', true ) );
            if ( !$publish_count || apply_filters( 'rtcl_listing_log_ignore_publish_count', false ) ) {
                global $wpdb;
                $table = $wpdb->prefix . 'rtcl_posting_log';
                $log = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE post_id = %d AND user_id = %d AND status = 'new'", $listing->get_id(),
                    $listing->get_author_id() ) );
                if ( $log ) {
                    $wpdb->delete( $table, [
                        'post_id' => $listing->get_id(),
                        'user_id' => $listing->get_author_id(),
                        'status'  => 'new'
                    ], [ '%d', '%d', '%s' ] );
                } else {
                    $member = rtclStore()->factory->get_membership( $listing->get_author_id() );
                    if ( $member ) {
                        $member->add_post_count();
                    }
                }
            }
        }
    }

    public static function remove_membership_data( $user_id ) {
        global $wpdb;
        $membership_table = $wpdb->prefix . "rtcl_membership";
        $membership = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$membership_table} WHERE user_id = %d", $user_id )
        );
        if ( $membership ) {
            $wpdb->delete(
                $wpdb->prefix . "rtcl_membership_meta",
                [ 'membership_id' => $membership->id ],
                [ '%d' ]
            );
            $wpdb->delete(
                $wpdb->prefix . "rtcl_membership",
                [ 'id' => $membership->id ],
                [ '%d' ]
            );
        }
    }


    /**
     * @param WP_Error $error
     * @param array $data
     */
    public static function store_contact_form_validation( $error, $data ) {
        if ( empty( $data['store_id'] ) || empty( $data['name'] ) || empty( $data['email'] ) || empty( $data['message'] ) ) {
            $error->add( 'rtcl_field_required', esc_html__( 'Need to fill all the required field.', 'classified-listing-store' ) );
        }
    }
}