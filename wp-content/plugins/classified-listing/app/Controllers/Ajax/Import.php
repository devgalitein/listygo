<?php

namespace Rtcl\Controllers\Ajax;

use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options;

class Import {

	function __construct() {
		add_action( 'wp_ajax_rtcl_import_location', [ $this, 'rtcl_import_location' ] );
		add_action( 'wp_ajax_rtcl_import_category', [ $this, 'rtcl_import_category' ] );
		add_action( 'wp_ajax_rtcl_import_settings', [ $this, 'rtcl_import_settings' ] );
		add_action( 'wp_ajax_rtcl_import_ad_types', [ $this, 'rtcl_import_ad_types' ] );
		add_action( 'wp_ajax_rtcl_import_listings', [ $this, 'rtcl_import_listings' ] );
		add_action( 'wp_ajax_rtcl_import_process_listing_data', [ $this, 'process_listing_data' ] );
	}

	/**
	 * @throws \Exception
	 */
	public function process_listing_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Unauthorized access!!!', 'classified-listing' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $_POST[ rtcl()->nonceId ] ?? '', rtcl()->nonceText ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Session Expired!!', 'classified-listing' ),
				]
			);
		}


		$return = [
			'success' => false,
			'message' => esc_html__( 'Something wrong. Not added any listing!!', 'classified-listing' ),
		];

		$rows = $_POST['rows'];
		parse_str( $_POST['formData'], $formData );
		$map_to = $formData['map_to'];

		if ( empty( $rows ) || ! is_array( $rows ) ) {
			$return['message'] = esc_html__( 'Not found listings!', 'classified-listing' );
			wp_send_json( $return );
		}

		if ( empty( $map_to ) || ! is_array( $map_to ) ) {
			$return['message'] = esc_html__( 'Please, assign data field for listings!', 'classified-listing' );
			wp_send_json( $return );
		}

		$inserted_posts = [];

		foreach ( $rows as $row ) {
			$postarr   = [];
			$meta_data = [];
			$cat_id    = null;
			$loc_id    = null;
			$tag_id    = null;
			$loc_ids   = [];
			$cat_ids   = [];
			$tag_ids   = [];
			$author    = [];

			foreach ( $row as $field => $data ) {
				$key = $map_to[ $field ];

				switch ( $key ) {
					case 'rtcl_title':
						$postarr['post_title'] = $data;
						break;
					case 'rtcl_content':
						$postarr['post_content'] = $data;
						break;
					case 'rtcl_excerpt':
						$postarr['post_excerpt'] = $data;
						break;
					case 'post_date':
						$postarr['post_date'] = $data;
						break;
					case 'post_author':
						$postarr['post_author'] = $data;
						$author[ $key ]         = $data;
						break;
					case 'post_author_fname':
					case 'post_author_lname':
					case 'post_author_uname':
					case 'post_author_display_name':
					case 'post_author_email':
					case 'post_author_role':
						$author[ $key ] = $data;
						break;
					case 'rtcl_listing_status':
						$postarr['post_status'] = $data;
						break;
					case 'rtcl_gallery':
						$attachment_ids = [];
						if ( ! empty( $data ) ) {
							$attachment_ids = $this->rtcl_process_image( $data );
						}
						break;
					case 'rtcl_tax_category':
						$name = trim( $data );
						if ( $name ) {
							$terms  = explode( '>', $name );
							$limit  = apply_filters( 'rtcl_import_terms_hierarchy_limit', 3 );
							$parent = 0;
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $index => $slug ) {

									if ( $limit === $index ) {
										break;
									}

									$check_term = term_exists( $slug, rtcl()->category );

									if ( ! $check_term ) {
										$cat_id    = wp_insert_term(
											$slug,
											rtcl()->category,
											[
												'slug'   => sanitize_title( $slug ),
												'parent' => $parent,
											]
										);
										$cat_ids[] = absint( $cat_id['term_id'] );
									} else {
										$cat_id          = $check_term;
										$existing_parent = $cat_id['term_id'];
										while ( $existing_parent ) {
											$cat_ids[]       = absint( $existing_parent );
											$existing_term   = get_term_by( 'ID', $existing_parent, rtcl()->category );
											$existing_parent = $existing_term->parent;
										}
									}

									$parent = $cat_id['term_id'] ?? 0;

								}
							}
						}
						break;
					case 'rtcl_tax_location':
						$name = trim( $data );
						if ( $name ) {
							$terms  = explode( '>', $name );
							$limit  = apply_filters( 'rtcl_import_terms_hierarchy_limit', 3 );
							$parent = 0;
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $index => $slug ) {

									if ( $limit === $index ) {
										break;
									}

									$check_term = term_exists( $slug, rtcl()->location );

									if ( ! $check_term ) {
										$loc_id    = wp_insert_term(
											$slug,
											rtcl()->location,
											[
												'slug'   => sanitize_title( $slug ),
												'parent' => $parent,
											]
										);
										$loc_ids[] = absint( $loc_id['term_id'] );
									} else {
										$loc_id          = $check_term;
										$existing_parent = $loc_id['term_id'];
										while ( $existing_parent ) {
											$loc_ids[]       = absint( $existing_parent );
											$existing_term   = get_term_by( 'ID', $existing_parent, rtcl()->location );
											$existing_parent = $existing_term->parent;
										}
									}

									$parent = $loc_id['term_id'] ?? 0;

								}
							}
						}
						break;
					case 'rtcl_tax_tags':
						if ( ! empty( $data ) ) {
							$name  = trim( $data );
							$terms = explode( ',', $name );

							if ( ! empty( $terms ) ) {
								foreach ( $terms as $index => $name ) {
									$check_term = term_exists( $name, rtcl()->tag );

									if ( ! $check_term ) {
										$tag_id = wp_insert_term(
											$name,
											rtcl()->tag,
											[
												'slug' => sanitize_title( $name ),
											]
										);
										if ( ! is_wp_error( $tag_id ) ) {
											$tag_ids[] = absint( $tag_id['term_id'] );
										}
									} else {
										$tag_id    = $check_term;
										$tag_ids[] = absint( $tag_id['term_id'] );
									}
								}
							}
						}

						break;
					case '_rtcl_video_urls':
						if ( ! empty( $data ) ) {
							$urls              = explode( ',', $data );
							$meta_data[ $key ] = $urls;
						}
						break;
					case '_rtcl_social_profiles':
						if ( ! empty( $data ) ) {
							$socials      = [];
							$all_profiles = explode( ',', $data );

							$social_profile_list = array_keys( Options::get_social_profiles_list() );

							if ( is_array( $all_profiles ) ) {
								foreach ( $all_profiles as $profile ) {
									$social_profile = explode( '|', trim( $profile ) );

									$social_key = isset( $social_profile[0] ) ? trim( $social_profile[0] ) : '';
									$social_url = isset( $social_profile[1] ) ? trim( $social_profile[1] ) : '';
									$social_url = $social_url && filter_var( $social_url, FILTER_VALIDATE_URL ) ? $social_url : '';

									if ( $social_key && $social_url && in_array( $social_key, $social_profile_list ) ) {
										$socials[ $social_key ] = $social_url;
									}
								}
							}

							if ( ! empty( $socials ) ) {
								$meta_data[ $key ] = $socials;
							}
						}
						break;
					default:
						if ( ! empty( $data ) ) {
							$meta_data[ $key ] = $data;
						}
				}
			}

			if ( ! empty( $postarr ) ) {
				$postarr['post_type'] = rtcl()->post_type;

				if ( ! empty( $author ) && ! empty( $author['post_author_email'] ) ) {
					$user_id = email_exists( $author['post_author_email'] );
					if ( isset( $author['post_author_uname'] ) && ! username_exists( $author['post_author_uname'] ) ) {
						$user_name = $author['post_author_uname'];
					} else {
						$part_of_email = explode( '@', $author['post_author_email'] );
						$user_name     = username_exists( $part_of_email[0] ) ? $author['post_author_email'] : $part_of_email[0];
					}
					if ( ! $user_id ) {
						$password      = wp_generate_password();
						$new_user_data = apply_filters(
							'rtcl_import_new_user_data',
							[
								'user_login'   => $user_name,
								'user_pass'    => $password,
								'user_email'   => $author['post_author_email'],
								'first_name'   => $author['post_author_fname'] ?? '',
								'last_name'    => $author['post_author_lname'] ?? '',
								'display_name' => $author['post_author_display_name'] ?? $user_name,
								'role'         => $author['post_author_role'] ?? get_option( 'default_role', 'subscriber' ),
							]
						);
						$customer_id   = wp_insert_user( $new_user_data );
						if ( ! is_wp_error( $customer_id ) ) {
							$user_id = $customer_id;
							if ( Functions::get_option_item( 'rtcl_email_notifications_settings', 'notify_users', 'user_import', 'multi_checkbox' ) ) {
								rtcl()->mailer()->emails['User_Import_Email_To_User']->trigger( $user_id, $new_user_data );
							}
						} else {
							$user_id = $author['post_author'];
						}
					}
					$postarr['post_author'] = $user_id;
				}

				$post_id = wp_insert_post( $postarr );
				if ( ! is_wp_error( $post_id ) ) {
					$inserted_posts[] = $post_id;
					if ( ! empty( $meta_data ) ) {
						wp_update_post(
							[
								'ID'         => $post_id,
								'meta_input' => $meta_data,
							]
						);
					}

					if ( ! is_wp_error( $cat_id ) && ! empty( $cat_ids ) ) {
						wp_set_object_terms( $post_id, $cat_ids, rtcl()->category );
					}

					if ( ! is_wp_error( $loc_id ) && ! empty( $loc_ids ) ) {
						wp_set_object_terms( $post_id, $loc_ids, rtcl()->location );
					}

					if ( ! is_wp_error( $tag_id ) && ! empty( $tag_ids ) ) {
						wp_set_object_terms( $post_id, $tag_ids, rtcl()->tag );
					}

					if ( ! empty( $attachment_ids ) && is_array( $attachment_ids ) ) {
						$attachment_ids = array_map( 'intval', $attachment_ids );
						$attachment_ids = array_filter( $attachment_ids );
						set_post_thumbnail( $post_id, $attachment_ids[0] );
						foreach ( $attachment_ids as $attachment_id ) {
							wp_update_post(
								[
									'ID'          => $attachment_id,
									'post_parent' => $post_id,
								]
							);
						}
						update_post_meta( $post_id, '_rtcl_attachments_order', $attachment_ids );
					}
				}
			}
		}

		if ( ! empty( $inserted_posts ) ) {
			$return['success'] = true;
			$return['message'] = sprintf( __( 'Added %d listings.', 'classified-listing' ), count( $inserted_posts ) );
		}

		wp_send_json( $return );
	}

	public function rtcl_import_category() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Unauthorized access!!!', 'classified-listing' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $_POST[ rtcl()->nonceId ] ?? '', rtcl()->nonceText ) ) {
			wp_send_json(
				[
					'success' => false,
					'data'    => null,
					'message' => esc_html__( 'Session Expired!!', 'classified-listing' ),
				]
			);

			return;
		}

		$data   = $_REQUEST['data'];
		$return = $this->create_term( rtcl()->category, $data );
		wp_send_json( $return );
	}

	public function rtcl_import_location() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Unauthorized access!!!', 'classified-listing' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $_POST[ rtcl()->nonceId ] ?? '', rtcl()->nonceText ) ) {
			wp_send_json(
				[
					'success' => false,
					'data'    => null,
					'message' => esc_html__( 'Session Expired!!', 'classified-listing' ),
				]
			);

			return;
		}

		$data   = $_REQUEST['data'];
		$return = $this->create_term( rtcl()->location, $data );
		wp_send_json( $return );
	}

	public function rtcl_import_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Unauthorized access!!!', 'classified-listing' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $_POST[ rtcl()->nonceId ] ?? '', rtcl()->nonceText ) ) {
			wp_send_json(
				[
					'success' => false,
					'data'    => null,
					'message' => esc_html__( 'Session Expired!!', 'classified-listing' ),
				]
			);
		}

		$data   = $_REQUEST['data'];
		$return = $this->update_settings( $data );
		wp_send_json( $return );
	}

	public function rtcl_import_ad_types() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Unauthorized access!!!', 'classified-listing' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $_POST[ rtcl()->nonceId ] ?? '', rtcl()->nonceText ) ) {
			wp_send_json(
				[
					'success' => false,
					'data'    => null,
					'message' => esc_html__( 'Session Expired!!', 'classified-listing' ),
				]
			);
		}

		$data = $_REQUEST['data'];

		$return = [
			'success' => false,
			'data'    => null,
			'message' => '',
		];

		$title      = $data['value'] ?? '';
		$get_option = get_option( 'rtcl_listing_types' );
		if ( ! $get_option ) {
			$get_option = Functions::get_listing_types();
		}

		if ( is_array( $get_option ) && array_key_exists( $data['key'], $get_option ) ) {
			$return['success'] = 'exist';
			$return['data']    = '';
			$return['message'] = sprintf( __( '%s is already exist!', 'classified-listing' ), $title );
		} else {
			$get_option[ $data['key'] ] = $title;
			$update                     = update_option( 'rtcl_listing_types', $get_option );
			if ( $update ) {
				$return['success'] = true;
				$return['message'] = sprintf( __( '%s Successfully Created', 'classified-listing' ), $title );
			} else {
				$return['message'] = __( 'Error!!! in ', 'classified-listing' ) . $title;
			}
		}

		wp_send_json( $return );
	}

	private function create_term( $taxonomy, $data ) {

		$data = wp_parse_args(
			$data,
			[
				'name'        => '',
				'slug'        => '',
				'parent'      => 0,
				'description' => '',
				'order'       => 0,
				'meta'        => [],
				'child'       => [],
			]
		);

		$return = [
			'success' => false,
			'data'    => null,
			'message' => __( 'Item is empty.', 'classified-listing' ),
		];
		try {
			if ( $data['name'] ) {
				$unique     = ! empty( $data['slug'] ) ? $data['slug'] : $data['name'];
				$term_exist = term_exists( $unique, $taxonomy );
				if ( empty( $term_exist ) ) {
					$term = wp_insert_term(
						$data['name'],
						$taxonomy,
						[
							'parent'      => isset( $data['parent'] ) ? absint( $data['parent'] ) : 0,
							'slug'        => $data['slug'],
							'description' => $data['description'],
						]
					);
					if ( ! is_wp_error( $term ) ) {
						update_term_meta( $term['term_id'], '_rtcl_order', absint( $data['order'] ) );
						if ( is_array( $data['meta'] ) && ! empty( $data['meta'] ) ) {
							foreach ( $data['meta'] as $meta_key => $meta ) {
								update_term_meta( $term['term_id'], $meta_key, $meta );
							}
						}
						$return['success'] = true;
						$return['data']    = $term;
						/* translators:  name */
						$return['message'] = sprintf( esc_html__( '%s Successfully created', 'classified-listing' ), esc_html( $data['name'] ) );
					} else {
						$return['message'] = $term->get_error_message();
					}
				} else {
					$return['success'] = true;
					$return['data']    = $term_exist;
					/* translators:  Name */
					$return['message'] = sprintf( esc_html__( '%s is already exist', 'classified-listing' ), esc_html( $data['name'] ) );
				}
			}
		} catch ( \Exception $e ) {
			$return['message'] = $e->getMessage();
		}

		return $return;
	}

	private function update_settings( $data ) {
		$return = [
			'success' => false,
			'data'    => null,
			'message' => '',
		];

		$key = $data['key'];

		if ( ! empty( $key ) ) {
			$defaults = get_option( $key, [] );
			if ( ! empty( $defaults ) ) {
				$args = wp_parse_args( $data['value'], $defaults );
				update_option( $key, $args );
				$return['success'] = true;
				$return['message'] = sprintf( __( '%s Successfully Created', 'classified-listing' ), $key );
			}
		}

		wp_send_json( $return );
	}

	public function rtcl_import_listings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				[
					'success' => false,
					'message' => esc_html__( 'Unauthorized access!!!', 'classified-listing' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $_POST[ rtcl()->nonceId ] ?? '', rtcl()->nonceText ) ) {
			wp_send_json(
				[
					'success' => false,
					'data'    => null,
					'message' => esc_html__( 'Session Expired!!', 'classified-listing' ),
				]
			);
		}

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$results = [
			'success' => false,
			'data'    => null,
			'message' => '',
		];

		$file = $_FILES['file'] ?? [];
		$rows = [];
		if ( ! empty( $file ) ) {
			Filters::beforeUpload();
			$status = wp_handle_upload(
				$file,
				[
					'test_form' => false,
				]
			);
			Filters::afterUpload();
			if ( $status && ! isset( $status['error'] ) ) {
				$filename = $status['file'];
				$filetype = wp_check_filetype( basename( $filename ) );
				if ( is_file( $filename ) ) {
					$results['file_url'] = $filename;

					$fopen = fopen( $filename, 'r' );

					while ( ( $data = fgetcsv( $fopen, 0, ',' ) ) !== false ) {
						$rows[] = $data;
					}

					$row_count = count( $rows );

					if ( $row_count > apply_filters( 'rtcl_import_listings_limit', 101 ) ) {
						$results['message'] = sprintf(
							esc_html__(
								'Please, add maximum 100 listings in one file. You added %s listings!!',
								'classified-listing'
							),
							$row_count - 1
						);
					} else {
						$title_row          = $row_count > 1 ? array_shift( $rows ) : [];
						$results['rawData'] = $rows;
						if ( ! empty( $title_row ) ) {
							$results['success'] = true;
							ob_start();
							?>
							<form class="rtcl-listings-import-mapping-form" id="rtcl-listings-import-mapping-form"
								  name="rtcl-listings-import-mapping-form"
								  method="post">
								<header>
									<h5><?php esc_html_e( 'Map CSV fields to listings', 'classified-listing' ); ?></h5>
									<p>
										<?php
										esc_html_e(
											'Select fields from your CSV file to map against listings fields, or to ignore during import.',
											'classified-listing'
										);
										?>
									</p>
								</header>
								<div class="rtcl-importer-mapping-table-wrapper">
									<table class="rtcl-importer-mapping-table">
										<thead>
										<tr>
											<th><?php esc_html_e( 'Column name', 'classified-listing' ); ?></th>
											<th><?php esc_html_e( 'Map to field', 'classified-listing' ); ?></th>
										</tr>
										</thead>
										<tbody>
										<?php
										foreach ( $title_row as $index => $title ) {
											?>
											<tr>
												<td><?php echo esc_html( $title ); ?></td>
												<td>
													<select class="rtcl_map_to"
															name="map_to[<?php echo esc_attr( $index ); ?>]">
														<option
															value=""><?php _e( 'Do not import', 'classified-listing' ); ?></option>
														<?php
														$fields = $this->get_listing_import_fields();
														foreach ( $fields as $key => $field ) {
															?>
															<option
																value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field ); ?></option>
															<?php
														}
														?>
													</select>
												</td>
											</tr>
											<?php
										}
										?>
										</tbody>
									</table>
									<button type="submit" id="rtcl_listings_import_submit" class="btn btn-primary">
										<?php
										_e(
											'Continue',
											'classified-listing'
										);
										?>
									</button>
								</div>
							</form>
							<?php
							$results['data'] = ob_get_clean();
						} else {
							$results['message'] = esc_html__( 'Please, add at least one listings!!', 'classified-listing' );
						}
					}
					fclose( $fopen );
					unlink( $filename );
				} else {
					$results['message'] = esc_html__( 'File does not exist!!', 'classified-listing' );
				}
			} else {
				$results['message'] = esc_html__( 'Error in file upload!!', 'classified-listing' );
			}
		} else {
			$results['message'] = esc_html__( 'File not found!!', 'classified-listing' );
		}

		wp_send_json( $results );
	}

	private function get_listing_import_fields() {
		return array_merge( Functions::get_listings_default_fields(), Functions::get_listings_custom_fields() );
	}

	private function rtcl_process_image( $data, $post_id = 0 ) {
		$images  = explode( ',', $data );
		$gallery = [];

		foreach ( $images as $image_url ) {
			$image_title   = preg_replace( '/\.[^.]+$/', '', basename( $image_url ) );
			$attachment_id = $this->upload_image( $image_url, $image_title, $post_id );
			if ( ! is_wp_error( $attachment_id ) ) {
				$gallery[] = $attachment_id;
			}
		}

		return $gallery;
	}

	private function upload_image( $image_url, $image_title, $post_id = 0 ) {
		set_time_limit( 150 );
		wp_raise_memory_limit( 'image' );
		Filters::beforeUpload();
		$attachment_id = media_sideload_image( $image_url, $post_id, $image_title, 'id' );
		Filters::afterUpload();

		return $attachment_id;
	}
}