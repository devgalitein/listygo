<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.6
 */

namespace radiustheme\listygo;

use WP_REST_Request;
use Rtcl\Models\Listing;
use Rtcl\Helpers\Functions;
use Rtcl\Controllers\Hooks\Filters;

class Helper {
    use IconTrait;
    use CustomQueryTrait;
    use ResourceLoadTrait;
    use DataTrait;
    use LayoutTrait;
    use SocialShares;
    use SvgTrait;

    public static function rt_the_logo_light() {
        if ( has_custom_logo() ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $logo_light = wp_get_attachment_image( $custom_logo_id, 'full' );
        } else {
            if ( !empty( RDTListygo::$options['logo'] ) ) {
                $logo_light = wp_get_attachment_image( RDTListygo::$options['logo'], 'full' );
            } else {
                $logo_light = '';
            }
        }
        return $logo_light;
    }

    public static function rt_the_logo_dark() {
        if ( has_custom_logo() ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $logo_dark = wp_get_attachment_image( $custom_logo_id, 'full' );
        } else {
            if ( !empty( RDTListygo::$options['logo_dark'] ) ) {
                $logo_dark = wp_get_attachment_image( RDTListygo::$options['logo_dark'], 'full' );
            } else {
                $logo_dark = '';
            }
        }
        return $logo_dark;
    }

    public static function listygo_excerpt( $limit ) {
        if ( !empty( $limit ) ) {
            $limit = $limit;
        } else {
            $limit = 0;
        }
        $excerpt = explode( ' ', get_the_excerpt(), $limit );
        if ( count( $excerpt ) >= $limit ) {
            array_pop( $excerpt );
            $excerpt = implode( " ", $excerpt ) . '';
        } else {
            $excerpt = implode( " ", $excerpt );
        }
        $excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );

        return $excerpt;
    }

    public static function custom_sidebar_fields() {
        $listygo = LISTYGO_THEME_PREFIX_VAR;
        $sidebar_fields = [];

        $sidebar_fields['sidebar'] = esc_html__( 'Sidebar', 'listygo' );
        $sidebar_fields['sidebar-project'] = esc_html__( 'Project Sidebar ', 'listygo' );

        $sidebars = get_option( "{$listygo}_custom_sidebars", [] );
        if ( $sidebars ) {
            foreach ( $sidebars as $sidebar ) {
                $sidebar_fields[$sidebar['id']] = $sidebar['name'];
            }
        }

        return $sidebar_fields;
    }

    public static function pagination( $max_num_pages = false ) {
        global $wp_query;
        $max = $max_num_pages ? $max_num_pages : $wp_query->max_num_pages;
        $max = intval( $max );

        /** Stop execution if there's only 1 page */
        if ( $max <= 1 ) return;

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

        /**    Add current page to the array */
        if ( $paged >= 1 )
            $links[] = $paged;

        /**    Add the pages around the current page to the array */
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
        include LISTYGO_THEME_VIEW_DIR . 'pagination.php';
    }

    public static function comments_callback( $comment, $args, $depth ) {
        include LISTYGO_THEME_VIEW_DIR . 'comments-callback.php';
    }

    public static function nav_menu_args() {
        $listygo = LISTYGO_THEME_PREFIX_VAR;
        $nav_menu_args = [
            'theme_location' => 'primary',
            'container'      => 'ul',
            'menu_class'     => 'main-menu',
        ];
        return $nav_menu_args;
    }

    public static function copyright_menu_args() {
        $nav_menu_args = [
            'theme_location' => 'crmenu',
            'depth'          => 1,
            'container'      => 'ul',
            'menu_class'     => 'footer-bottom-link',
        ];
        return $nav_menu_args;
    }

    public static function requires( $filename, $dir = false ) {
        if ( $dir ) {
            $child_file = get_stylesheet_directory() . '/' . $dir . '/' . $filename;
            if ( file_exists( $child_file ) ) {
                $file = $child_file;
            } else {
                $file = get_template_directory() . '/' . $dir . '/' . $filename;
            }
        } else {
            $child_file = get_stylesheet_directory() . '/inc/' . $filename;
            if ( file_exists( $child_file ) ) {
                $file = $child_file;
            } else {
                $file = LISTYGO_THEME_INC_DIR . $filename;
            }
        }

        require_once $file;
    }

    /**
     * Classified Listing Plugin
     *
     */
    public static function get_custom_listing_template( $template, $echo = true, $args = [] ) {
        $template = 'classified-listing/custom/' . $template;
        if ( $echo ) {
            self::get_template_part( $template, $args );
        } else {
            $template .= '.php';
            return $template;
        }
    }

    /**
     * Custom store template
     *
     * @param $template
     * @param $echo
     * @param $args
     *
     * @return string|void
     */
    public static function get_custom_store_template( $template, $echo = true, $args = [] ) {
        $template = 'classified-listing/store/custom/' . $template;
        if ( $echo ) {
            self::get_template_part( $template, $args );
        } else {
            $template .= '.php';

            return $template;
        }
    }

    public static function listygo_base_color() {
        return apply_filters( 'listygo_base_color', RDTListygo::$options['listygo_base_color'] );
    }

    public static function listygo_body_color() {
        return apply_filters( 'listygo_body_color', RDTListygo::$options['listygo_body_color'] );
    }

    public static function listygo_heading_color() {
        return apply_filters( 'listygo_heading_color', RDTListygo::$options['listygo_heading_color'] );
    }

    public static function push_food_image_api( $post_id, $key ) {
        $food_list = get_post_meta( $post_id, $key, true );
        if ( !empty( $food_list ) ):
            foreach ( $food_list as $index => $f_group ) {
                $new_food_list = [];
                foreach ( $f_group['food_list'] as $food_item ) {
                    if ( !empty( $food_item['attachment_id'] ) ) {
                        $food_item['image_url'] = wp_get_attachment_image_url( $food_item['attachment_id'], 'full' );
                    }
                    $new_food_list[] = $food_item;
                }
                $food_list[$index]['food_list'] = $new_food_list;
            }
        endif;
        return $food_list;
    }

    public static function push_chamber_image_api( $post_id, $key ) {
        $chamber_list = get_post_meta( $post_id, $key, true );
        if ( !empty( $chamber_list ) ):
            foreach ( $chamber_list as $index => $chamber_item ) {
                if ( !empty( $chamber_item['chamber_img'] ) ) {
                    $chamber_item['chamber_img'] = absint($chamber_item['chamber_img']);
                    $chamber_item['image_url'] = wp_get_attachment_image_url( $chamber_item['chamber_img'], 'full' );
                }
                $chamber_list[$index] = $chamber_item;
            }
        endif;
        return $chamber_list;
    }

    /**
     * @param WP_REST_Request $request
     * @param Listing $listing
     * @return void
     */
    public static function process_chambers_data( $request, $listing ) {
        $files = $request->get_file_params();
        $dr_json_raw_data = $request->get_param( 'listygo_doctor_chamber_list' );
        $images = !empty( $files['listygo_doctor_chamber_images']['name'] ) && is_array( $files['listygo_doctor_chamber_images']['name'] ) ? $files['listygo_doctor_chamber_images'] : [];
        $existingData = get_post_meta( $listing->get_id(), 'listygo_doctor_chamber', true );
        if ( !empty( $dr_json_raw_data ) ) {
            $dr_raw_data = json_decode( $dr_json_raw_data, true );
            if ( !empty( $dr_raw_data ) && is_array( $dr_raw_data ) ) {
                $docData = [];
                foreach ( $dr_raw_data as $index => $item ) {
                    $_item = [
                        'cname'     => sanitize_text_field( $item['cname'] ),
                        'time'      => sanitize_text_field( $item['time'] ),
                        'phone'     => sanitize_text_field( $item['phone'] ),
                        'cloaction' => sanitize_textarea_field( $item['cloaction'] )
                    ];
                    if ( !empty( $images['name'][$index] ) ) {
                        $_image = [
                            'name'     => $images['name'][$index],
                            'type'     => $images['type'][$index],
                            'tmp_name' => $images['tmp_name'][$index],
                            'error'    => $images['error'][$index],
                            'size'     => $images['size'][$index]
                        ];

                        if ( $attach_id = self::save_listygo_image( $_image ) ) {
                            $_item['chamber_img'] = $attach_id;
                            if ( !empty( $item['chamber_img'] ) ) {
                                wp_delete_attachment( $item['chamber_img'], true );
                            }
                        }
                    } else {
                        if ( empty( $item['chamber_img'] ) ) {
                            if ( !empty($existingData[$index]['chamber_img']) ) {
                                wp_delete_attachment( $existingData[$index]['chamber_img'], true );
                            }
                        } else {
                            $_item['chamber_img'] = absint( $item['chamber_img'] );
                        }
                    }

                    $docData[] = $_item;
                }
                if ( !empty( $docData ) ) {
                    update_post_meta( $listing->get_id(), 'listygo_doctor_chamber', $docData );
                } else {
                    delete_post_meta( $listing->get_id(), 'listygo_doctor_chamber' );
                }
            }
        }
    }

    /**
     * @param $request
     * @param Listing $listing
     *
     * @return void
     */
    public static function process_restaurant_data( $request, $listing ) {
        $restaurant_json_raw_data = $request->get_param( 'listygo_food_menu_list' );

        $files = $request->get_file_params();
        $images = !empty( $files['listygo_food_images']['name'] ) && is_array( $files['listygo_food_images']['name'] ) ? $files['listygo_food_images'] : [];
        $existingData = get_post_meta( $listing->get_id(), 'listygo_food_list', true );
        if ( !empty( $restaurant_json_raw_data ) ) {
            $restaurant_groups = json_decode( $restaurant_json_raw_data, true );
            $foodMenuList = [];
            if ( !empty( $restaurant_groups ) && is_array( $restaurant_groups ) ) {
                foreach ( $restaurant_groups as $group_index => $group ) {
                    $foodList = $group['food_list'];
                    $_group = [
                        'gtitle'    => sanitize_text_field( $group['gtitle'] ),
                        'food_list' => []
                    ];

                    if ( is_array( $foodList ) && !empty( $foodList ) ) {
                        foreach ( $foodList as $item_key => $item ) {
                            $_item = [
                                'title'       => sanitize_text_field( $item['title'] ),
                                'foodprice'   => sanitize_text_field( $item['foodprice'] ),
                                'description' => sanitize_textarea_field( $item['description'] )
                            ];
                            $_item_key = $group_index . '_' . $item_key;
                            if ( !empty( $images['name'][$_item_key] ) ) {
                                $foodImage = [
                                    'name'     => $images['name'][$_item_key],
                                    'type'     => $images['type'][$_item_key],
                                    'tmp_name' => $images['tmp_name'][$_item_key],
                                    'error'    => $images['error'][$_item_key],
                                    'size'     => $images['size'][$_item_key]
                                ];
                                if ( $attach_id = self::save_listygo_image( $foodImage ) ) {
                                    $_item['attachment_id'] = $attach_id;
                                    if ( !empty( $item['attachment_id'] ) ) {
                                        wp_delete_attachment( $item['attachment_id'], true );
                                    }
                                }
                            } else {
                                if ( empty( $item['attachment_id'] ) ) {
                                    if ( !empty($existingData[$group_index][$_item_key]['attachment_id']) ) {
                                        wp_delete_attachment( $existingData[$group_index][$_item_key]['attachment_id'], true );
                                    }
                                } else {
                                    $_item['attachment_id'] = absint( $item['attachment_id'] );
                                }
                            }
                            $_group['food_list'][$item_key] = $_item;
                        }
                    }
                    $foodMenuList[$group_index] = $_group;
                }
            }

            if ( !empty( $foodMenuList ) ) {
                update_post_meta( $listing->get_id(), 'listygo_food_list', $foodMenuList );
            } else {
                delete_post_meta( $listing->get_id(), 'listygo_food_list' );
            }
        }
    }


    /**
     * @param array $request
     *
     * @return int|null
     */
    public static function save_listygo_image( $file ) {
        if ( !empty( $file['name'] ) ) {

            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            Filters::beforeUpload();
            $status = wp_handle_upload( $file, [ 'test_form' => false ] );
            Filters::afterUpload();
            if ( $status && !isset( $status['error'] ) ) {
                $filename = $status['file'];
                $filetype = wp_check_filetype( basename( $filename ) );
                $wp_upload_dir = wp_upload_dir();
                $attachment = [
                    'guid'           => $wp_upload_dir['url'] . '/'
                        . basename( $filename ),
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '',
                        basename( $filename ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                ];
                $attach_id = wp_insert_attachment( $attachment, $filename );
                if ( !is_wp_error( $attach_id ) ) {
                    wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $filename ) );
                    return $attach_id;
                }
            }
        }

        return null;
    }

    /**
     * @param $request
     * @param $listing
     *
     * @return void
     */
    public static function process_listing_logo( $request, $listing ) {
        $files = $request->get_file_params();
        if ( !empty( $files['listygo_listing_logo']['name'] ) ) {
            $logo = $files['listygo_listing_logo'];
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            Filters::beforeUpload();
            $status = wp_handle_upload( $logo, [ 'test_form' => false ] );
            Filters::afterUpload();
            if ( $status && !isset( $status['error'] ) ) {
                $filename = $status['file'];
                $filetype = wp_check_filetype( basename( $filename ) );
                $wp_upload_dir = wp_upload_dir();
                $attachment = [
                    'guid'           => $wp_upload_dir['url'] . '/'
                        . basename( $filename ),
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace( '/\.[^.]+$/', '',
                        basename( $filename ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                ];
                $attach_id = wp_insert_attachment( $attachment, $filename );
                if ( !is_wp_error( $attach_id ) ) {
                    wp_update_attachment_metadata( $attach_id,
                        wp_generate_attachment_metadata( $attach_id, $filename ) );
                    update_post_meta( $listing->get_id(), 'listing_logo_img', $attach_id );
                }
            }
        }
    }

    /**
     * @param $file_path
     *
     * @return int|\WP_Error
     */
    public static function save_image_from_path( $file_path ) {
        // Check if the file exists
        if ( !file_exists( $file_path ) ) {
            return new \WP_Error( 'file_not_found', 'File not found.' );
        }

        // Get the file type and extension
        $file_info = pathinfo( $file_path );
        $file_type = wp_check_filetype( $file_info['basename'] );
        $filename = 'attch_image_' . uniqid() . '.' . $file_type['ext'];

        // Set the upload directory
        $upload_dir = wp_upload_dir();

        if ( wp_mkdir_p( $upload_dir['path'] ) ) {
            $new_file_path = $upload_dir['path'] . '/' . $filename;
        } else {
            $new_file_path = $upload_dir['basedir'] . '/' . $filename;
        }

        // Copy the file to the upload directory
        if ( !copy( $file_path, $new_file_path ) ) {
            return new \WP_Error( 'file_copy_failed', 'Failed to copy file.' );
        }

        // Create an attachment and add it to the media library
        $attachment = [
            'post_mime_type' => $file_type['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attach_id = wp_insert_attachment( $attachment, $new_file_path );

        // Include the image.php file
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $new_file_path );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }

    /**
     * @param $post_id
     * @param $taxonomy
     *
     * @return array|string
     */
    public static function get_parent_category( $post_id, $taxonomy ) {
        $post_categories = get_the_terms( $post_id, $taxonomy );
        if ( $post_categories ) {
            $parent_categories = '';
            foreach ( $post_categories as $category ) {
                if ( $category->parent == 0 ) {
                    $parent_categories = $category->slug;
                } else {
                    $term = get_term( $category->parent, $taxonomy );
                    $parent_categories = $term->slug;
                }
            }
            return $parent_categories;
        }
        return [];
    }

	/**
	 * If listing archive map is enable
	 * @return bool
	 */
	static function is_map_enabled() {
		return (bool) Functions::get_option_item( 'rtcl_archive_listing_settings', 'enable_archive_map', false, 'checkbox' );
	}

}