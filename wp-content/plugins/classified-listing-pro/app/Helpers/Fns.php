<?php

namespace RtclPro\Helpers;

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use Rtcl\Helpers\Utility;
use Rtcl\Models\Listing;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;
use RtclPro\Models\Conversation;
use RtclPro\Models\FBFilterCFValidation;
use WP_Error;
use WP_Query;

class Fns {

	public static function verify_pro_nonce() {
		$nonce     = isset( $_REQUEST[ rtclPro()->nonceId ] ) ? $_REQUEST[ rtclPro()->nonceId ] : null; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		$nonceText = rtclPro()->nonceText;
		if ( wp_verify_nonce( $nonce, $nonceText ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param Listing $listing
	 *
	 * @return bool
	 */
	static function is_popular( $listing ) {
		$views             = absint( get_post_meta( $listing->get_id(), '_views', true ) );
		$popular_threshold = Functions::get_option_item( 'rtcl_general_listing_label_settings', 'popular_listing_threshold', 0, 'number' );
		
		if ( $views >= $popular_threshold ) {
			return true;
		}

		return false;
	}

	/**
	 * Display the classes for the listing div
	 *
	 * @param string|array $classes One or more classes to add to the class list.
	 *
	 * @since 1.5.4
	 */
	static function top_listings_wrap_class( $classes = [] ) {
		$classes[] = 'rtcl-listings';
		$classes[] = apply_filters( 'rtcl_listings_view_class', 'rtcl-list-view' );
		$classes[] = apply_filters( 'rtcl_top_listings_grid_columns', 'columns-4' );
		$classes   = apply_filters( 'rtcl_top_listings_wrap_class', $classes );
		$classes   = array_map( 'esc_attr', array_unique( array_filter( $classes ) ) );
		if ( ! empty( $classes ) ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}

	static function top_listings_query() {
		$post_per_pare = Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_top_per_page', 2 );
		$query_args    = [
			'post_type'      => rtcl()->post_type,
			'post_status'    => 'publish',
			'posts_per_page' => absint( $post_per_pare ),
			'orderby'        => 'rand'
		];
		/**
		 * @var $query WP_Query
		 */
		$query = $GLOBALS['wp_query'];

		if ( isset( $_GET['q'] ) ) {
			$query_args['s'] = Functions::clean( $_GET['q'] );
		}
		if ( ! empty( $query->tax_query->queries ) ) {
			$query_args['tax_query'] = $query->tax_query->queries;
		}
		if ( ! empty( $query->tax_query->queries ) ) {
			$query_args['meta_query'] = $query->meta_query->queries;
		}
		$query_args['meta_query'][] = [
			'key'     => '_top',
			'value'   => 1,
			'compare' => '='
		];

		if ( count( $query_args['meta_query'] ) > 1 ) {
			$query_args['meta_query']['relation'] = "AND";
		}
		$query = new WP_Query( apply_filters( 'rtcl_top_listings_query_args', $query_args ) );

		return apply_filters( 'rtcl_top_listings_query', $query );
	}

	static function is_enable_top_listings() {
		return Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_enable_top_listing', false, 'checkbox' );
	}

	public static function comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; // WPCS: override ok.
		Functions::get_template( 'listing/review', [
			'comment' => $comment,
			'args'    => $args,
			'depth'   => $depth,
		], '', rtclPro()->get_plugin_template_path() );
	}


	/**
	 * Get HTML for star rating.
	 *
	 * @param float $rating Rating being shown.
	 * @param int   $count  Total number of ratings.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function get_star_rating_html( $rating, $count = 0 ) {
		$html = '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';

		if ( 0 < $count ) {
			/* translators: 1: rating 2: rating count */
			$html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count,
				'classified-listing-pro' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>',
				'<span class="rating">' . esc_html( $count ) . '</span>' );
		} else {
			/* translators: %s: rating */
			$html .= sprintf( esc_html__( 'Rated %s out of 5', 'classified-listing-pro' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>' );
		}

		$html .= '</span>';

		return apply_filters( 'rtcl_get_star_rating_html', $html, $rating, $count );
	}

	/**
	 * Get HTML for ratings.
	 * s     *
	 *
	 * @param float $rating Rating being shown.
	 * @param int   $count  Total number of ratings.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function get_rating_html( $rating, $count = 0 ) {
		if ( 0 < $rating ) {
			$title = sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count,
				'classified-listing-pro' ), esc_html( $rating ), esc_html( $count ) );
			$html  = '<div class="star-rating" title="' . $title . '">';
			$html  .= self::get_star_rating_html( $rating, $count );
			$html  .= '</div>';
		} else {
			$html = '';
		}

		return apply_filters( 'rtcl_listing_get_rating_html', $html, $rating, $count );
	}

	/**
	 * @deprecated
	 * @use Rtcl\Helpers\Functions::is_enable_map()
	 */
	public static function is_enable_map() {
		_deprecated_function( __METHOD__, '2.0.9', 'Rtcl\Helpers\Functions::is_enable_map()' );

		return Functions::is_enable_map();
	}

	/**
	 * @return bool|int|mixed|null
	 * @deprecated
	 * @use Rtcl\Helpers\Functions::has_map()
	 */
	static function has_map() {
		_deprecated_function( __METHOD__, '2.0.9', 'Rtcl\Helpers\Functions::has_map()' );

		return Functions::has_map();
	}

	/**
	 * @return bool|int|mixed|null
	 * @deprecated
	 * @use Rtcl\Helpers\Functions::get_map_type()
	 */
	static function get_map_type() {
		_deprecated_function( __METHOD__, '2.0.9', 'Rtcl\Helpers\Functions::get_map_type()' );

		return Functions::get_map_type();
	}

	/**
	 * @param integer $listing_id
	 *
	 * @return bool
	 * @deprecated
	 * @use Rtcl\Helpers\Functions::hide_map()
	 */
	static function hide_map( $listing_id ) {
		_deprecated_function( __METHOD__, '2.0.9', 'Rtcl\Helpers\Functions::hide_map()' );

		return Functions::hide_map( $listing_id );
	}

	static function is_online( $author_id ) {
		$online_status = get_user_meta( $author_id, 'online_status', true );

		return ! empty( $online_status ) && $online_status >= current_time( 'timestamp' );
	}

	static function is_enable_chat() {
		return Functions::get_option_item( 'rtcl_chat_settings', 'enable', false, 'checkbox' );
	}

	static function is_enable_chat_unread_message_email() {
		return self::is_enable_chat() && Functions::get_option_item( 'rtcl_chat_settings', 'unread_message_email', false, 'checkbox' );
	}

	public static function is_enable_compare() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_compare', false, 'checkbox' );
	}

	public static function get_compare_limit() {
		return absint( apply_filters( 'rtcl_compare_limit', Functions::get_option_item( 'rtcl_general_settings', 'compare_limit', 4, 'number' ) ) );
	}

	public static function is_enable_quick_view() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_quick_view', false, 'checkbox' );
	}

	public static function check_license() {
		return apply_filters( 'rtcl_check_license', true );
	}

	/**
	 * @return mixed
	 * @deprecated
	 * @use Rtcl\Helpers\Functions::location_type()
	 */
	public static function location_type() {
		_deprecated_function( __METHOD__, '2.0.9', 'FRtcl\Helpers\unctions::location_type()' );

		return Functions::location_type();
	}

	public static function is_enable_mark_as_sold() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_mark_as_sold', false, 'checkbox' );
	}

	/**
	 *
	 * @param integer $listing_id
	 *
	 * @return bool
	 */
	public static function is_mark_as_sold( $listing_id ) {
		return (bool) absint( get_post_meta( $listing_id, '_rtcl_mark_as_sold', true ) );
	}

	/**
	 * Check if has any option for register user only
	 *
	 * @return bool
	 */
	public static function registered_user_only( $key ) {
		return $key && Functions::get_option_item( 'rtcl_single_listing_settings', 'registered_only', $key, 'multi_checkbox' );
	}


	/**
	 * Does user needs email validation?
	 *
	 * @param $user_id
	 *
	 * @return bool
	 */
	public static function needs_validation( $user_id ) {
		return boolval( get_user_meta( $user_id, 'rtcl_verification_key', true ) );
	}


	static function is_wc_payment_enabled() {
		return Functions::is_wc_activated() && Functions::get_option_item( 'rtcl_payment_woo-payment', 'enabled', false, 'checkbox' );
	}

	static function is_woo_order_autocomplete_disable() {
		return Functions::get_option_item( 'rtcl_payment_woo-payment', 'order_autocomplete_disable', false, 'checkbox' );
	}

	/**
	 * @param int $con_id conversation id
	 * @param int $user_id
	 *
	 * @return bool
	 */
	static function is_on_conversation( $con_id, $user_id = 0 ) {
		$user_id    = ! empty( $user_id ) ? absint( $user_id ) : get_current_user_id();
		$con_status = get_user_meta( $user_id, '_rtcl_conversation_status', true );
		if ( ! $con_status ) {
			return false;
		}
		$con_status = explode( ':', $con_status );
		if ( count( $con_status ) !== 2 ) {
			return false;
		}
		$conv_status_time = $con_status[0];
		$conv_status_id   = absint( $con_status[1] );

		return $conv_status_id === $con_id && ! empty( $conv_status_time ) && $conv_status_time >= current_time( 'timestamp' );
	}

	/**
	 * @param int $con_id conversation id
	 * @param int $user_id
	 *
	 * @return void
	 */
	public static function update_chat_conversation_status( $con_id, $user_id = 0 ) {
		$user_id = ! empty( $user_id ) ? absint( $user_id ) : get_current_user_id();
		$time    = current_time( 'timestamp' ) + (int) apply_filters( 'rtcl_chat_conversation_status_seconds', 15 );
		update_user_meta( $user_id, '_rtcl_conversation_status', "$time:$con_id" );
	}

	/**
	 * @param array $params
	 * @param array $filterData
	 *
	 * @return array
	 */
	public static function get_ajax_filter_cs_items( $params, $filterData ) {
		$filterData    = ! empty( $filterData['items'] ) ? $filterData['items'] : $filterData;
		$cfFilters     = [];
		$hideAbleCount = 6;
		$filterTypes   = [
			'text',
			'textarea',
			'number',
			'checkbox',
			'select',
			'radio',
			'date',
		];
		
		$filterTypes = apply_filters( 'rtcl_ajax_filter_allow_fields', $filterTypes );

		$categoryIds = [];
		if ( ! empty( $params['filter_category'] ) ) {
			if ( is_array( $params['filter_category'] ) ) {
				$categoryIds = array_filter( array_map( 'absint', $params['filter_category'] ) );
			} else {
				$categoryIds = trim( sanitize_text_field( wp_unslash( $params['filter_category'] ) ) );
				$categoryIds = $categoryIds ? explode( ',', $categoryIds ) : [];
				$categoryIds = ! empty( $categoryIds ) ? array_filter( array_map( 'absint', $categoryIds ) ) : [];
			}
		}

		if ( FBHelper::isEnabled() ) {
			if ( empty( $params['directory'] ) || $params['directory'] === 'all' ) {
				$directory = 'all';
			} else {
				if ( is_numeric( $params['directory'] ) ) {
					$directory = $params['directory'];
				} else {
					if ( ! is_array( $params['directory'] ) ) {
						$directory = explode( ',', $params['directory'] );
					}
					if ( ! empty( $directory ) ) {
						$directory = array_filter( array_map( 'absint', $directory ) );
						$directory = ! empty( $directory ) ? $directory : 0;
					} else {
						$directory = 0;
					}
				}
			}

			$directoryData = FBHelper::getDirectoryData( $directory );
			$found_key     = array_search( 'cf', array_column( $filterData, 'id' ) );

			// Reorder custom field for filter display
			$cfFields = ! empty( $directoryData['custom'] ) ? $directoryData['custom'] : [];

			if ( $found_key !== false && ! empty( $cfFields ) ) {
				$_cfFilterField = $filterData[ $found_key ];

				if ( ! empty( $_cfFilterField['fields_order'] ) && is_array( $_cfFilterField['fields_order'] ) ) {
					$sortedAllCfFilters = [];
					$cfFieldsModded     = [];
					foreach ( $cfFields as $key => $cfField ) {
						$cfFieldsModded[ $cfField['uuid'] ] = $cfField;
					}
					foreach ( $_cfFilterField['fields_order'] as $cfFilterFieldUuid ) {
						if ( ! empty( $cfFieldsModded[ $cfFilterFieldUuid ] ) ) {
							$sortedAllCfFilters[] = $cfFieldsModded[ $cfFilterFieldUuid ];
							unset( $cfFieldsModded[ $cfFilterFieldUuid ] );
						}
					}
					$cfFields = $sortedAllCfFilters + $cfFieldsModded;
				}
			}

			if ( ! empty( $cfFields ) ) {
				
				$validation = new FBFilterCFValidation( $params, $directoryData);
				foreach ( $cfFields as $custom_field ) {
					if ( empty( $custom_field['filterable'] ) || ! in_array( $custom_field['element'], $filterTypes ) ) {
						continue;
					}
					$field = new FBField( $custom_field );
					
					if(!apply_filters('rtcl_cf_filterable_disable_logic', false, $field, $validation) && !$field->getData('filterable_disable_logic', false)) {
						if ( !$validation->fieldIsValidCondition( $field ) ) {
							continue;
						}
//						Old category only validation
//						if ( ! $field->isValidCategoryCondition( $categoryIds, $directoryData ) ) {
//							continue;
//						}
					}
					
					$isActive   = false;
					$metaKey    = $field->getMetaKey();
					$filterName = 'cf_' . $metaKey;
					$field_html = null;
					$values     = ! empty( $params[ $filterName ] ) ? ( is_array( $params[ $filterName ] ) ? array_filter( array_map( function ( $param ) {
						return trim( sanitize_text_field( wp_unslash( $param ) ) );
					}, $params[ $filterName ] ) ) : trim( sanitize_text_field( wp_unslash( $params[ $filterName ] ) ) ) ) : '';
					if ( 'number' == $field->getElement() ) {
						$filterInput = ! empty( $values )
							? ( is_array( $values ) ? $values : explode( ',', $values ) )
							: [
								null,
								null
							];
						$filterInput = array_map( 'intval', $filterInput );
						$fMinValue   = ! empty( $filterInput[0] ) ? esc_attr( $filterInput[0] ) : null;
						$fMaxValue   = ! empty( $filterInput[1] ) ? esc_attr( $filterInput[1] ) : null;
						$isActive    = $fMinValue || $fMaxValue;
						$field_html  .= sprintf( '<div class="rtcl-filter-number-field-wrap min-max">
                                                                    <input id="filter-cf-number-%1$s-min" name="filter-cf-number-%1$s-min" type="number" value="%2$s" class="rtcl-filter-number-field min form-control" placeholder="%3$s">									
                                                                    <input id="filter-cf-number-%1$s-max" name="filter-cf-number-%1$s-max" type="number" value="%4$s" class="rtcl-filter-number-field max form-control" placeholder="%5$s">
                                                                </div>',
							$metaKey,
							$fMinValue,
							esc_html__( 'Min.', 'classified-listing-pro' ),
							$fMaxValue,
							esc_html__( 'Max.', 'classified-listing-pro' )
						);
					} elseif ( 'date' == $field->getElement() ) {

						$field_html .= sprintf( '<div class="rtcl-filter-date-field-wrap">
														<input id="filter-cf-date-%1$s" role="presentation" autocomplete="off" name="filter-cf-date-%1$s" type="text" value="%2$s" data-options="%4$s" class="form-control rtcl-filter-date-field" placeholder="%3$s">									
													</div>',
							esc_attr( $filterName ),
							esc_attr( $values ),
							esc_html__( 'Date', 'classified-listing-pro' ),
							htmlspecialchars(
								wp_json_encode(
									$field->getDateFieldOptions(
										[
											'singleDatePicker' => $field->getData( 'filterable_date_type' ) === 'single',
											'autoUpdateInput'  => false,
										]
									)
								)
							)
						);
						$isActive   = ! empty( $values );
					} elseif ( in_array( $field->getElement(), [ 'text', 'textarea' ], true ) ) {
						$isActive         = ! empty( $values );
						$placeholder_text = sprintf( esc_html__( 'Search by %s', 'classified-listing-pro' ), $field->getLabel() );
						$field_html       .= sprintf( '<div class="rtcl-ajax-filter-text">
																	<label class="screen-reader-text" for="rtcl-ajax-filter-%1$s">%3$s ...</label>
                                                                    <input id="rtcl-ajax-filter-%1$s" name="%1$s" type="text"  role="presentation" autocomplete="off" value="%2$s" class="rtcl-form-control rtcl-filter-text-field" placeholder="%3$s">
                                                                    <i class="rtcl-clear-text rtcl-icon-trash"></i>
                                                                </div>',
							$filterName,
							$values,
							apply_filters( 'rtcl_ajax_filter_cf_text_field_placeholder', $placeholder_text, $field )
						);
					} else {
						$values  = is_string( $values ) ? explode( ',', $values ) : $values;
						$options = $field->getOptions();
						if ( ! empty( $options ) ) {
							$field_html .= '<div class="rtcl-ajax-filter-data">';
							$count      = 0;
							foreach ( $options as $option ) {
								$count ++;
								$option = wp_parse_args( $option, [ 'value' => '', 'label' => '' ] );
								$_value = $option['value'];
								$_label = $option['label'];

								$field_html .= sprintf( '<div class="rtcl-ajax-filter-data-item rtcl-filter-checkbox-item rtcl-filter-cf-%1$s%6$s">
															<div class="rtcl-ajax-filter-diiWrap">
																<input id="cf-%1$s" name="%2$s" value="%3$s" type="checkbox" class="rtcl-filter-checkbox"%4$s />
																<label for="cf-%1$s" class="rtcl-filter-checkbox-label">
																	<span class="rtcl-filter-checkbox-text">%5$s</span>
																</label>
															</div>
												</div>',
									esc_attr( $filterName . '-' . $_value ),
									$filterName,
									esc_attr( $_value ),
									in_array( $_value, $values ) ? ' checked' : '',
									esc_html( $_label ),
									$count >= $hideAbleCount ? ' hideAble' : '',
								);
							}
							if ( $count >= $hideAbleCount ) {
								$field_html .= '<div class="rtcl-more-less-btn">
													<div class="text more-text"><i class="rtcl-icon rtcl-icon-plus"></i>' . __( 'More',
										'classified-listing-pro' ) . '</div>
													<div class="text less-text"><i class="rtcl-icon rtcl-icon-minus"></i>' . __( 'Less',
										'classified-listing-pro' ) . '</div>
												</div>';
							}
							$field_html .= '</div>';
						}
						$field_html = apply_filters( 'rtcl_ajax_filter_cf_field_html', $field_html, $field, $params );
					}

					$options = [ 'name' => $filterName, 'field_type' => $field->getElement() ];
					$html    = apply_filters( 'rtcl_ajax_filter_cf_html',
						sprintf( '<div class="rtcl-ajax-filter-item rtcl-ajax-filter-cf-item is-open rtcl-filter_%1$s%2$s" data-cf-id="%1$s">
									                <div class="rtcl-filter-title-wrap">
									                    <div class="rtcl-filter-title">%3$s<span class="rtcl-reset rtcl-icon rtcl-icon-cw"></span></div>
									                    <i class="rtcl-icon rtcl-icon-angle-down"></i>
									                </div>
									                <div class="rtcl-filter-content" data-options="%4$s">%5$s</div>
									            </div>',
							$filterName,
							$isActive ? ' is-active' : '',
							$field->getLabel(),
							htmlspecialchars( wp_json_encode( $options ) ),
							$field_html
						),
						$field_html,
						$field
					);

					$cfFilters[] = [
						'options' => $options,
						'html'    => $html
					];
				}
			}
		} else {
			$c_ids = Functions::get_custom_field_ids( $categoryIds );
			if ( ! empty( $c_ids ) ) {
				foreach ( $c_ids as $c_id ) {
					$field = rtcl()->factory->get_custom_field( $c_id );
					if ( ! $field ) {
						continue;
					}
					if ( ! empty( $conditions = $field->getConditions() ) && is_array( $conditions ) ) {
						$isCValid = true;
						foreach ( $conditions as $group ) {
							$isGValid = true;
							foreach ( $group as $rule ) {
								$isValid    = true;
								$r_field_id = ! empty( $rule['field'] ) ? absint( $rule['field'] ) : 0;
								$operator   = ! empty( $rule['operator'] ) ? $rule['operator'] : '';
								if ( ! $r_field_id || ! $operator ) {
									continue;
								}
								$r_field_id = apply_filters( 'rtcl_wpml_cf_field_id', $r_field_id, $rule, $group, $field );
								$r_value    = ! empty( $rule['value'] ) ? $rule['value'] : '';
								$d_value    = ! empty( $params[ 'cf_' . $r_field_id ] ) ? $params[ 'cf_' . $r_field_id ] : '';
								if ( $operator === '==empty' ) { // hasNoValue
									$isValid = empty( $d_value );
								} elseif ( $operator === '!=empty' ) { // hasValue  -- ANY value
									$isValid = ! empty( $d_value );
								} elseif ( $operator === '==' ) { // equalTo
									if ( is_array( $d_value ) ) {
										$isValid = in_array( $r_value, $d_value );
									} else {
										$isValid = strtolower( $d_value ) == strtolower( $r_value );
									}
								} elseif ( $operator === '!=' ) { // notEqualTo
									if ( is_array( $d_value ) ) {
										$isValid = ! in_array( $r_value, $d_value );
									} else {
										$isValid = strtolower( $d_value ) !== strtolower( $r_value );
									}
								} elseif ( $operator === '==pattern' && ! empty( $r_value ) ) { // patternMatch
									if ( is_array( $d_value ) ) {
										$isPatternValid = false;
										foreach ( $d_value as $_ ) {
											preg_match( "/$r_value/", $_, $matches );
											if ( ! empty( $matches ) ) {
												$isPatternValid = true;
												break;
											}
										}
										$isValid = $isPatternValid;
									} else {
										preg_match( "/$r_value/", $d_value, $matches );
										$isValid = ! empty( $matches );
									}
								} elseif ( $operator === '==contains' ) { // contains
									if ( is_array( $d_value ) ) {
										$isContainsValid = false;
										foreach ( $d_value as $_ ) {
											if ( strpos( (string) $r_value, (string) $_ ) !== false ) {
												$isContainsValid = true;
												break;
											}
										}
										$isValid = $isContainsValid;
									} else {
										if ( empty( $d_value ) ) {
											$isValid = false;
										} else {
											$isValid = strpos( (string) $r_value, (string) $d_value ) !== false;
										}
									}
								}
								if ( ! $isValid ) {
									$isGValid = false;
									break;
								}
							}
							if ( $isGValid ) {
								$isCValid = true;
								break;
							} else {
								$isCValid = false;
							}
						}
						if ( ! $isCValid ) {
							continue;
						}
					}
					if ( in_array( $field->getType(), $filterTypes ) && $field->isSearchable() ) {
						$field_html = null;
						$metaKey    = $field->getMetaKey();
						$filterName = 'cf_' . $field->getFieldId();
						$isActive   = false;
						$values     = ! empty( $params[ $filterName ] ) ? ( is_array( $params[ $filterName ] ) ? array_filter( array_map( function ( $param ) {
							return trim( sanitize_text_field( wp_unslash( $param ) ) );
						}, $params[ $filterName ] ) ) : trim( sanitize_text_field( wp_unslash( $params[ $filterName ] ) ) ) ) : '';

						if ( $field->getType() == 'number' ) {
							$filterInput  = ! empty( $values )
								? ( is_array( $values ) ? $values : explode( ',', $values ) )
								: [
									null,
									null
								];
							$filterInput  = array_map( 'intval', $filterInput );
							$fMinValue    = ! empty( $filterInput[0] ) ? esc_attr( $filterInput[0] ) : null;
							$fMaxValue    = ! empty( $filterInput[1] ) ? esc_attr( $filterInput[1] ) : null;
							$min_settings = $field->getMin();
							$max_settings = ! empty( $field->getMax() ) ? 'data-max=' . absint( $field->getMax() ) : '';
							$field_html   .= sprintf( '<div class="rtcl-filter-number-field-wrap min-max">
                                                                    <input id="filter-cf-number-%1$s-min" name="filter-cf-number-%1$s-min" type="number" value="%2$s" class="rtcl-filter-number-field min form-control" data-min="%6$s" placeholder="%3$s">									
                                                                    <input id="filter-cf-number-%1$s-max" name="filter-cf-number-%1$s-max" type="number" value="%4$s" class="rtcl-filter-number-field max form-control" %7$s placeholder="%5$s">
                                                                </div>',
								$metaKey,
								$fMinValue,
								esc_html__( 'Min.', 'classified-listing-pro' ),
								$fMaxValue,
								esc_html__( 'Max.', 'classified-listing-pro' ),
								absint( $min_settings ),
								$max_settings
							);
							$isActive     = $fMinValue || $fMaxValue;
						} elseif ( $field->getType() == 'date' ) {
							$field_html .= sprintf( '<div class="rtcl-filter-date-field-wrap">
														<input id="filter-cf-date-%1$s" role="presentation" autocomplete="off" name="filter-cf-date-%1$s" type="text" value="%2$s" data-options="%4$s" class="form-control rtcl-filter-date-field" placeholder="%3$s">									
													</div>',
								esc_attr( $metaKey ),
								esc_attr( $values ),
								esc_html__( 'Date', 'classified-listing-pro' ),
								htmlspecialchars(
									wp_json_encode(
										$field->getDateFieldOptions(
											[
												'singleDatePicker' => $field->getDateSearchableType() === 'single',
												'autoUpdateInput'  => false,
											]
										)
									)
								)
							);
							$isActive   = ! empty( $values );
						} elseif ( in_array( $field->getType(), [ 'text', 'textarea' ], true ) ) {
							$placeholder_text = sprintf( esc_html__( 'Search by %s', 'classified-listing-pro' ), $field->getLabel() );
							$field_html       .= sprintf( '<div class="rtcl-ajax-filter-text">
																	<label class="screen-reader-text" for="rtcl-ajax-filter-%1$s">%3$s ...</label>
                                                                    <input role="presentation" autocomplete="off" id="rtcl-ajax-filter-%1$s" name="%1$s" type="text" value="%2$s" class="rtcl-form-control rtcl-filter-text-field" placeholder="%3$s">
                                                                    <i class="rtcl-clear-text rtcl-icon-trash"></i>
                                                                </div>',
								$filterName,
								$values,
								apply_filters( 'rtcl_ajax_filter_cf_text_field_placeholder', $placeholder_text, $field )
							);
							$isActive         = ! empty( $values );
						} elseif ( in_array( $field->getType(), [ 'radio', 'select', 'checkbox' ], true ) ) {
							$values  = is_string( $values ) ? explode( ',', $values ) : $values;
							$options = $field->getOptions();
							if ( ! empty( $options['choices'] ) ) {
								$field_html .= '<div class="rtcl-ajax-filter-data">';
								$count      = 0;
								foreach ( $options['choices'] as $key => $option ) {
									$count ++;
									$field_html .= sprintf( '<div class="rtcl-ajax-filter-data-item rtcl-filter-checkbox-item rtcl-filter-cf-%1$s%6$s">
															<div class="rtcl-ajax-filter-diiWrap">
																<input id="cf-%1$s" name="%2$s" value="%3$s" type="checkbox" class="rtcl-filter-checkbox"%4$s />
																<label for="cf-%1$s" class="rtcl-filter-checkbox-label">
																	<span class="rtcl-filter-checkbox-text">%5$s</span>
																</label>
															</div>
												</div>',
										esc_attr( $field->getMetaKey() . '-' . $key ),
										$filterName,
										esc_attr( $key ),
										in_array( $key, $values ) ? ' checked' : '',
										esc_html( $option ),
										$count >= $hideAbleCount ? ' hideAble' : '',
									);
								}
								if ( $count >= $hideAbleCount ) {
									$field_html .= '<div class="rtcl-more-less-btn">
													<div class="text more-text"><i class="rtcl-icon rtcl-icon-plus"></i>' . __( 'More',
											'classified-listing-pro' ) . '</div>
													<div class="text less-text"><i class="rtcl-icon rtcl-icon-minus"></i>' . __( 'Less',
											'classified-listing-pro' ) . '</div>
												</div>';
								}
								$field_html .= '</div>';
							}
						}
						$field_html = apply_filters( 'rtcl_ajax_filter_old_cf_field_html', $field_html, $field );
						$fieldType  = $field->getType();
						if ( in_array( $field->getType(), [ 'checkbox', 'radio', 'select' ] ) ) {
							$fieldType = 'checkbox';
						}
						$options = [ 'name' => $filterName, 'field_type' => $fieldType, 'cf_type' => 'old' ];
						$html    = apply_filters(
							'rtcl_ajax_filter_old_cf_html',
							sprintf( '<div class="rtcl-ajax-filter-item rtcl-ajax-filter-cf-item is-open rtcl-filter_cf_%1$s%2$s" data-cf-id="%1$s">
									                <div class="rtcl-filter-title-wrap">
									                    <div class="rtcl-filter-title">%3$s<span class="rtcl-reset rtcl-icon rtcl-icon-cw"></span></div>
									                    <i class="rtcl-icon rtcl-icon-angle-down"></i>
									                </div>
									                <div class="rtcl-filter-content" data-options="%4$s">%5$s</div>
									            </div>',
								$field->getFieldId(),
								$isActive ? ' is-active' : '',
								$field->getLabel(),
								htmlspecialchars( wp_json_encode( $options ) ),
								$field_html
							),
							$field_html,
							$field
						);

						$cfFilters[] = [
							'options' => $options,
							'html'    => $html
						];
					}
				}
			}
		}

		return $cfFilters;
	}


	/**
	 * @param integer $visitor_id
	 * @param integer $author_id
	 * @param integer $listing_id
	 * @param integer $con_id
	 *
	 * @return Conversation|null
	 */
	public static function getConversationByVisitorIdAuthorIdListingId( int $visitor_id, int $author_id, int $listing_id, $con_id = 0 ) {
		$db    = rtcl()->db();
		$table = $db->prefix . Conversation::CONV_TABLE;
		$object
		       = $db->get_row( $db->prepare( "SELECT * FROM {$table} WHERE ( ( sender_id = %d AND recipient_id = %d ) OR ( sender_id = %d AND recipient_id = %d ) ) AND sender_delete = 0 AND recipient_delete = 0 AND listing_id = %d",
			$visitor_id, $author_id, $author_id, $visitor_id, $listing_id ) );
		if ( ! empty( $object ) ) {
			return new Conversation( $object );
		}

		return null;
	}

	/**
	 * @param string $message
	 * @param array  $bad_word_list
	 *
	 * @return string|WP_Error
	 */
	public static function filter_bad_words( string $message, array $bad_word_list ) {
		$blockBadWords = apply_filters( 'rtcl_chat_block_bad_words', true );
		if ( $blockBadWords ) {
			foreach ( $bad_word_list as $word ) {
				$word = trim( $word );
				if ( $word !== '' ) {
					if ( preg_match( '/\b' . preg_quote( $word, '/' ) . '\b/i', $message, $matches ) ) {
						return new WP_Error( 'rtcl_chat_bar_words_error',
							sprintf( __( "Your message contains inappropriate words (%s).", 'classified-listing' ), implode( ', ', $matches ) ) );
					}
				}
			}

		} else {
			foreach ( $bad_word_list as $word ) {
				$word = trim( $word );
				if ( $word !== '' ) {
					$pattern = '/\b' . preg_quote( $word, '/' ) . '\b/i';
					$message = preg_replace( $pattern, str_repeat( '*', strlen( $word ) ), $message );
				}
			}
		}

		return $message;
	}

	/**
	 * @param string $message
	 * @param array  $bad_word_list
	 *
	 * @return boolean
	 */
	public static function has_bad_words( string $message, array $bad_word_list ) {
		foreach ( $bad_word_list as $word ) {
			$word = trim( $word );
			if ( $word !== '' ) {
				if ( preg_match( '/\b' . preg_quote( $word, '/' ) . '\b/i', $message ) ) {
					return true;
				}
			}
		}

		return false;
	}

	public static function getChatAttachmentConfig() {
		$config = [
			'max_limit'   => 4,
			'max_size_mb' => 5, // Max size in MB
			'image_types' => [ 'jpeg', 'png', 'webp' ],
			'file_types'  => [
				'pdf',
				'msword', // .doc
				'vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
				'vnd.ms-excel', // .xls
				'vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			]
		];

		return apply_filters( 'rtcl_chat_attachment_config', $config );
	}

	public static function getChati18() {
		return [
			'listing_removed'     => esc_html__( "Listing is removed ...", "classified-listing-pro" ),
			'load_more'           => esc_html__( "Load More", "classified-listing-pro" ),
			'media'               => esc_html__( "Media", "classified-listing-pro" ),
			'file'                => esc_html__( "File", "classified-listing-pro" ),
			'attachment'          => esc_html__( "Attachment", "classified-listing-pro" ),
			'file_size_error'     => esc_html__( "File size must be under __MB", "classified-listing-pro" ),
			'file_type_error'     => esc_html__( "Only JPG, PNG, WEBP, PDF, DOC, DOCX, XLS, and XLSX files are allowed.", "classified-listing-pro" ),
			'chat_txt'            => esc_html__( "Chat", "classified-listing-pro" ),
			'input_placeholder'   => esc_html__( "Search listings ...", "classified-listing-pro" ),
			'loading'             => esc_html__( "Loading ...", "classified-listing-pro" ),
			'confirm'             => esc_html__( "Are you sure to delete.", "classified-listing-pro" ),
			'my_chat'             => esc_html__( "My Chats", "classified-listing-pro" ),
			'chat_with'           => esc_html__( "Chat With", "classified-listing-pro" ),
			'delete_chat'         => esc_html__( "Delete chat", "classified-listing-pro" ),
			'select_conversation' => esc_html__( "Please select a conversation", "classified-listing-pro" ),
			'no_conversation'     => esc_html__( "You have no conversation yet.", "classified-listing-pro" ),
			'message_placeholder' => esc_html__( "Type a message here", "classified-listing-pro" ),
			'no_permission'       => esc_html__( "No permission to start chat.", "classified-listing-pro" ),
			'server_error'        => esc_html__( "Server Error", "classified-listing-pro" ),
			'confirm_delete'      => esc_html__( "Are you sure to remove?", "classified-listing-pro" ),
		];
	}

	public static function getChatLocalizedData() {
		$dateTImeFormat = Utility::dateFormatPHPToMoment( Functions::date_format() ) . ' ' . Utility::dateFormatPHPToMoment( Functions::time_format() );

		return [
			'attachment'       => Fns::getChatAttachmentConfig(),
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			'refresh_interval' => apply_filters( 'rtcl_chat_refresh_interval', 20000 ), //sec
			rtcl()->nonceId    => wp_create_nonce( rtcl()->nonceText ),
			'rest_api_url'     => Link::get_rest_api_url(),
			'date_time_format' => apply_filters( 'rtcl_chat_date_time_format', $dateTImeFormat ),
			'current_user_id'  => get_current_user_id(),
			'lang'             => Fns::getChati18()
		];
	}

	public static function getFileType( $mime_type ): string {
		if ( strpos( $mime_type, 'image/' ) === 0 ) {
			return 'image';
		} elseif ( strpos( $mime_type, 'video/' ) === 0 ) {
			return 'video';
		} elseif ( strpos( $mime_type, 'audio/' ) === 0 ) {
			return 'audio';
		}

		return 'file';
	}

	public static function deleteFileByUrl( $file_url ) {
		$upload_dir = wp_upload_dir();

		// Make sure the file is within the uploads directory
		if ( strpos( $file_url, $upload_dir['baseurl'] ) === false ) {
			return false; // Not safe to delete
		}

		$relative_path = str_replace( $upload_dir['baseurl'], '', $file_url );
		$file_path     = $upload_dir['basedir'] . $relative_path;

		// Check and delete
		if ( file_exists( $file_path ) ) {
			return unlink( $file_path ); // returns true on success
		}

		return false;
	}
}
