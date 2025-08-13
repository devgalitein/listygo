<?php


namespace RtclPro\Controllers;


use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use RtclPro\Helpers\Installer;
use RtclPro\Helpers\PNHelper;
use RtclPro\Models\Conversation;
use RtclPro\Models\Message;
use RtclPro\Models\PushNotification;
use RtclPro\Models\RtclPusher;
use WP_Error;

class ChatController {
	public static function init() {
		if ( Fns::is_enable_chat() ) {
			add_action( 'wp_ajax_rtcl_chat_ajax_conversations_remove', [
				__CLASS__,
				'rtcl_chat_ajax_hide_conversations'
			] );
//			add_action( 'wp_ajax_rtcl_chat_ajax_message_remove', [
//				__CLASS__,
//				'rtcl_chat_ajax_message_remove'
//			] );
			add_action( 'wp_ajax_rtcl_chat_ajax_admin_get_conversations', [
				__CLASS__,
				'rtcl_chat_ajax_admin_get_conversations'
			] );
			add_action( 'wp_ajax_rtcl_chat_ajax_admin_conversations_remove', [
				__CLASS__,
				'rtcl_chat_ajax_admin_conversations_remove'
			] );

			add_action( 'wp_ajax_rtcl_chat_ajax_admin_message_remove', [
				__CLASS__,
				'rtcl_chat_ajax_admin_message_remove'
			] );
			add_action( 'wp_ajax_rtcl_chat_ajax_get_conversations', [ __CLASS__, 'rtcl_chat_ajax_get_conversations' ] );
			add_action( 'wp_ajax_rtcl_chat_ajax_start_conversation', [
				__CLASS__,
				'rtcl_chat_ajax_start_conversation'
			] );
			add_action( 'wp_ajax_nopriv_rtcl_chat_ajax_start_conversation', [
				__CLASS__,
				'rtcl_chat_ajax_start_conversation'
			] );
			add_action( 'wp_ajax_rtcl_chat_ajax_send_message', [ __CLASS__, 'rtcl_chat_ajax_send_message' ] );
			add_action( 'wp_ajax_rtcl_chat_ajax_visitor_send_message', [
				__CLASS__,
				'rtcl_chat_ajax_visitor_send_message'
			] );
			add_action( 'wp_ajax_rtcl_chat_ajax_get_messages', [ __CLASS__, 'rtcl_chat_ajax_get_messages' ] );
			add_action( 'wp_ajax_rtcl_chat_ajax_message_mark_as_read', [
				__CLASS__,
				'rtcl_chat_ajax_message_mark_as_read'
			] );
			add_action( 'wp_ajax_rtcl_chat_ajax_get_unread_message_num', [
				__CLASS__,
				'rtcl_chat_ajax_get_unread_message_num'
			] );
			add_filter( 'rtcl_chat_sanitize_message', [ __CLASS__, 'rtcl_chat_sanitize_message' ] );
			//add_filter('rtcl_before_delete_listing', [__CLASS__, 'delete_chat_conversation']); TODO : Add this when foreign key is removed from database
		}

		if ( is_admin() ) {
			add_action( 'init', [ __CLASS__, 'regenerate_chat_table' ] );
		}

	}

	static function regenerate_chat_table() {
		if ( isset( $_GET['rtcl_regenerate_chat_table'] ) && Functions::verify_nonce() ) {
			global $wpdb;

			$tables = [
				$wpdb->prefix . "rtcl_conversations",
				$wpdb->prefix . "rtcl_conversation_messages"
			];
			$wpdb->query( "SET SESSION foreign_key_checks = 0" );
			foreach ( $tables as $table ) {
				if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) === $table ) {
					$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
				}
			}
			$wpdb->query( "SET SESSION foreign_key_checks = 1" );

			$wpdb->hide_errors();

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$schemas = Installer::get_chat_table_schema();
			if ( !empty( $schemas ) ) {
				dbDelta( $schemas );
			}
			Functions::add_notice( __( "Chat table has been regenerated", 'classified-listing-pro' ) );
		}
	}

	static function delete_chat_conversation( $listing_id ) {
		global $wpdb;
		$ids = $wpdb->get_col( $wpdb->prepare(
			"SELECT con_id FROM {$wpdb->prefix}rtcl_conversations WHERE listing_id = %d LIMIT 500",
			$listing_id
		) );
		if ( !empty( $ids ) ) {
			$wpdb->query( sprintf( 'DELETE FROM %s WHERE con_id IN (%s)', $wpdb->prefix . 'rtcl_conversations', implode( ',', $ids ) ) );
		}
	}

	static function rtcl_chat_sanitize_message( $message ) {
		// Strip all tags
		$message = strip_tags( $message );

		// Limit the letter
		$limit = apply_filters( 'rtcl_chat_sanitize_message_character_limit', 300 );
		if ( strlen( $message ) > $limit ) {
			$message = mb_substr( $message, 0, $limit, "utf-8" );
		}

		return $message;
	}

	static function rtcl_chat_ajax_get_unread_message_num() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		wp_send_json_success( [ 'unread_count' => self::has_unread_messages() ] );
	}

	/*
	* Has unread messages?
	*/
	static public function has_unread_messages( $user_id = null ) {

		$count = 0;
		$user_id = $user_id ?: get_current_user_id();
		if ( $user_id ) {
			global $wpdb;

			$count
				= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(message_id) FROM {$wpdb->prefix}rtcl_conversations AS rc LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rc.con_id = rcm.con_id WHERE ( ( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 ) ) AND is_read = 0 AND source_id != %d",
				$user_id, $user_id, $user_id ) );
		}

		return apply_filters( 'rtcl_chat_has_unread_messages_count', absint( $count ), $user_id );
	}

	static function rtcl_chat_ajax_message_mark_as_read() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		$message_id = isset( $_POST['message_id'] ) ? absint( $_POST['message_id'] ) : 0;
		$user_id = get_current_user_id();
		if ( !$user_id && $message_id ) {
			wp_send_json_error( [ 'message' => __( 'You are not authorized to access this page.', 'classified-listing-pro' ) ] );
		}
		global $wpdb;
		$messageTable = $wpdb->prefix . Conversation::CONV_MESSAGE_TABLE;
		$message = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $messageTable WHERE message_id = %d", $message_id ) );
		if ( !$message ) {
			wp_send_json_error( [ 'message' => __( 'Message not found.', 'classified-listing-pro' ) ] );
		}

		$conversation = new Conversation( absint( $message->con_id ) );
		if ( !$conversation->get_id() ) {
			wp_send_json_error( [ 'message' => __( 'Conversation not found.', 'classified-listing-pro' ) ] );
		}
		if ( !in_array( $user_id, [ $conversation->recipient_id, $conversation->sender_id ] ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not authorized to access this page.', 'classified-listing-pro' ) ] );
		}
		$update = $wpdb->update(
			$wpdb->prefix . Conversation::CONV_MESSAGE_TABLE,
			[ 'is_read' => 1 ],
			[ 'message_id' => $message_id ],
		);
		if ( !$update ) {
			wp_send_json_error( [ 'message' => __( 'Error while updated read count', 'classified-listing-pro' ) ] );
		}
		$pusher = RtclPusher::getInstance();
		$message = new Message( $message->message_id );
		$dataObject = $message->getData();

		$receiverUserId = $dataObject->source_id == $conversation->sender_id ? $conversation->recipient_id : $conversation->sender_id;
		$pusher->trigger( [ 'chat-user-' . $conversation->sender_id, 'chat-user-' . $conversation->recipient_id ], 'message.update', [
			'message' => $dataObject
		] );
		Conversation::notifyUnreadCount( $receiverUserId, $conversation );
		wp_send_json_success( [ 'message' => __( 'Message marked as read successfully.', 'classified-listing-pro' ) ] );
	}

	static function rtcl_chat_ajax_hide_conversations() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		$conIds = !empty( $_POST['con_ids'] ) && is_array( $_POST['con_ids'] ) ? array_filter( array_map( 'absint', $_POST['con_ids'] ) ) : [];
		if ( empty( $conIds ) ) {
			wp_send_json_error( [ 'message' => __( 'No conversation id selected to remove.', 'classified-listing-pro' ) ] );
		}
		$user_id = get_current_user_id();
		global $wpdb;
		$conIds = implode( ',', $conIds );
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}rtcl_conversations SET sender_delete = ( CASE WHEN sender_id = %d THEN 1 ELSE sender_delete END ), recipient_delete = ( CASE WHEN recipient_id = %d THEN 1 ELSE recipient_delete END ) WHERE con_id IN ( "
			. esc_sql( $conIds ) . " )", $user_id, $user_id ) );

		wp_send_json_success( [ 'message' => __( 'Conversation(s) removed successfully.', 'classified-listing-pro' ) ] );
	}

	static function rtcl_chat_ajax_admin_message_remove() {

		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !current_user_can( 'manage_rtcl_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		$conId = !empty( $_POST['con_id'] ) ? absint( $_POST['con_id'] ) : 0;
		$messageId = !empty( $_POST['message_id'] ) ? absint( $_POST['message_id'] ) : 0;

		if ( !$conId || !$messageId ) {
			wp_send_json_error( [ 'message' => __( 'No conversation or message id selected to remove.', 'classified-listing-pro' ) ] );
		}
		global $wpdb;
		$currentUserId = get_current_user_id();
//		$existMessage = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rtcl_conversation_messages WHERE con_id = %d AND message_id = %d AND source_id = %d", $conId, $messageId, $currentUserId ) );
		$existMessage = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rtcl_conversation_messages WHERE con_id = %d AND message_id = %d", $conId, $messageId ) );

		if ( !$existMessage ) {
			wp_send_json_error( [ 'message' => __( 'No Message found to remove .', 'classified-listing-pro' ) ] );
		}

		$messageRemoved = $wpdb->delete(
			$wpdb->prefix . 'rtcl_conversation_messages',
			[ 'con_id' => $conId, 'message_id' => $messageId, 'source_id' => $currentUserId ],
			[ '%d', '%d', '%d' ],
		);

		if ( !$messageRemoved ) {
			wp_send_json_error( [ 'message' => __( 'Message not found to remove.', 'classified-listing-pro' ) ] );
		}
		// Delete file from server
		if ( !empty( $existMessage->meta ) ) {
			$rawMeta = json_decode( $existMessage->meta, true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				if ( is_array( $rawMeta ) && !empty( $rawMeta ) ) {
					foreach ( $rawMeta as $_meta ) {
						if ( !empty( $_meta['type'] ) && !empty( $_meta['url'] ) && in_array( $_meta['type'], [
								'image',
								'video',
								'audio',
								'file'
							] ) ) {
							Fns::deleteFileByUrl( $_meta['url'] );
						}
					}
				}
			}
		}

		wp_send_json_success( [ 'message' => __( 'Message removed successfully.', 'classified-listing-pro' ) ] );
	}

	static function rtcl_chat_ajax_admin_get_conversations() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !current_user_can( 'manage_rtcl_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing' ) ] );
		}
		$perPage = !empty( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 20;
		$page = !empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$conversations = self::_admin_fetch_conversations( [ 'per_page' => $perPage, 'page' => $page ] );
		wp_send_json_success( $conversations );
	}

	static function rtcl_chat_ajax_get_conversations() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		$user_id = get_current_user_id();
		$perPage = !empty( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 20;
		$page = !empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$q = !empty( $_POST['terms'] ) ? Functions::clean( wp_unslash( $_POST['terms'] ) ) : '';
		$conversations = self::_fetch_conversations( $user_id, [ 'per_page' => $perPage, 'page' => $page, 'q' => $q ] );
		wp_send_json_success( $conversations );
	}

	static function rtcl_chat_ajax_admin_conversations_remove() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !current_user_can( 'manage_rtcl_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		if ( !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => __( 'You are not authorized to access this page.', 'classified-listing-pro' ) ] );
		}
		$conIds = !empty( $_POST['conIds'] ) && is_array( $_POST['conIds'] ) ? array_filter( array_map( 'absint', $_POST['conIds'] ) ) : [];
		if ( empty( $conIds ) ) {
			wp_send_json_error( [ 'message' => __( 'No conversation id selected to remove.', 'classified-listing-pro' ) ] );
		}
		global $wpdb;

		$removeIds = [];
		foreach ( $conIds as $conId ) {
			$existConv = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rtcl_conversations WHERE con_id = %d", $conId ) );
			if ( $existConv ) {
				$messageRemoved = $wpdb->delete(
					$wpdb->prefix . 'rtcl_conversation_messages',
					[ 'con_id' => $conId ],
					[ '%d' ],
				);
				$deleted = $wpdb->delete(
					$wpdb->prefix . 'rtcl_conversations',
					[ 'con_id' => $conId ],
					[ '%d' ],
				);
				if ( $deleted ) {
					$removeIds[] = $conId;
				}
			}
		}

		if ( empty( $removeIds ) ) {
			wp_send_json_error( [ 'message' => __( 'No conversation id selected to remove.', 'classified-listing-pro' ) ] );
		}
		wp_send_json_success( [
			'conIds'  => $removeIds,
			'message' => __( 'Conversation(s) removed successfully.', 'classified-listing-pro' )
		] );
	}

	static function _search_conversations( $user_id, $search = '' ) {
		if ( !$user_id ) {
			return [];
		}

		global $wpdb;

		$query = $wpdb->prepare( "
			SELECT SQL_CALC_FOUND_ROWS rc.*, message, is_read, source_id, rcm.message as last_message, rcm.created_at as last_message_created_at, display_name, user_login, CASE WHEN sender_id = %d THEN recipient_id ELSE sender_id END AS other_id 
			FROM {$wpdb->prefix}rtcl_conversations AS rc 
				LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rcm.message_id = last_message_id 
				LEFT JOIN {$wpdb->base_prefix}users AS users ON users.ID = ( CASE WHEN sender_id = %d THEN recipient_id ELSE sender_id END ) 
				LEFT JOIN {$wpdb->prefix}posts AS posts ON posts.ID = rc.listing_id
			WHERE (( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 )) AND posts.post_title LIKE %s", $user_id,
			$user_id, $user_id, $user_id, '%' . $search . '%' );

		$query .= $wpdb->prepare( "ORDER BY rcm.created_at DESC LIMIT %d", 50 );
		$bdConversations = $wpdb->get_results( $query );
		$conversations = [];
		if ( !empty( $bdConversations ) ) {
			foreach ( $bdConversations as $conv ) {
				$conversation = new Conversation( (array)$conv );
				if ( $conversation->exist() ) {
					$conversations[] = $conversation->getData();
				}
			}
		}

		return [
			'data'       => $conversations,
			'pagination' => [
				'total'        => $query->found_comments,
				'per_page'     => $query->query_vars['number'],
				'current_page' => $query->query_vars['paged'],
				'total_pages'  => $query->max_num_pages
			]
		];
	}

	static function _fetch_conversations( $user_id, $data = [] ) {
		global $wpdb;
		$data = wp_parse_args( $data, [
			'per_page' => 20,
			'page'     => 1,
			'q'        => '',
		] );

		$per_page = absint( $data['per_page'] );
		$per_page = $per_page ?: 20;
		$page = absint( $data['page'] );
		$page = $page ?: 1;
		$offset = ( $page - 1 ) * $per_page;
		$q = Functions::clean( wp_unslash( $data['q'] ) );
		$bdConversations = $conversations = [];
		$sql_posts_total = 0;
		if ( $user_id ) {
			if ( $q ) {
				$query = $wpdb->prepare( "
			SELECT SQL_CALC_FOUND_ROWS rc.*, message, is_read, source_id, rcm.message as last_message, rcm.created_at as last_message_created_at, display_name, user_login, CASE WHEN sender_id = %d THEN recipient_id ELSE sender_id END AS other_id 
			FROM {$wpdb->prefix}rtcl_conversations AS rc 
				LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rcm.message_id = last_message_id 
				LEFT JOIN {$wpdb->base_prefix}users AS users ON users.ID = ( CASE WHEN sender_id = %d THEN recipient_id ELSE sender_id END ) 
				LEFT JOIN {$wpdb->prefix}posts AS posts ON posts.ID = rc.listing_id
			WHERE (( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 )) AND posts.post_title LIKE %s", $user_id,
					$user_id, $user_id, $user_id, '%' . $q . '%' );
			} else {
				$query = $wpdb->prepare( "
			SELECT SQL_CALC_FOUND_ROWS rc.*, message, is_read, source_id, rcm.message as last_message, rcm.created_at as last_message_created_at, display_name, user_login, CASE WHEN sender_id = %d THEN recipient_id ELSE sender_id END AS other_id 
			FROM {$wpdb->prefix}rtcl_conversations AS rc 
				LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rcm.message_id = last_message_id 
				LEFT JOIN {$wpdb->base_prefix}users AS users ON users.ID = ( CASE WHEN sender_id = %d THEN recipient_id ELSE sender_id END ) 
			WHERE (( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 ))", $user_id, $user_id, $user_id, $user_id );
			}
			$query .= $wpdb->prepare( "ORDER BY rcm.created_at DESC LIMIT %d, %d", $offset, $per_page );
			$bdConversations = $wpdb->get_results( $query );
			$sql_posts_total = $wpdb->get_var( "SELECT FOUND_ROWS();" );
			$max_num_pages = ceil( $sql_posts_total / $per_page );
		}

		if ( !empty( $bdConversations ) ) {
			foreach ( $bdConversations as $conv ) {
				$convA = (array)$conv;
				$conversation = new Conversation( $convA );
				if ( $conversation->exist() ) {
					$conversations[] = wp_parse_args( $conversation->getData(), $convA );
				}
			}
		}

		return [
			'data'       => $conversations,
			'pagination' => [
				'total'        => absint( $sql_posts_total ),
				'per_page'     => $per_page,
				'current_page' => $page,
				'total_pages'  => $max_num_pages
			]
		];
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	static function _admin_fetch_conversations( $data = [] ) {
		global $wpdb;
		$data = wp_parse_args( $data, [
			'per_page' => 20,
			'page'     => 1,
			'q'        => '',
		] );

		$per_page = absint( $data['per_page'] );
		$per_page = $per_page ?: 20;
		$page = absint( $data['page'] );
		$page = $page ?: 1;
		$offset = ( $page - 1 ) * $per_page;
		$q = Functions::clean( wp_unslash( $data['q'] ) );
		if ( $q ) {
			$query = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS rc.*, message, is_read, source_id, rcm.message as last_message, rcm.created_at as last_message_created_at
						FROM {$wpdb->prefix}rtcl_conversations AS rc 
						LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rcm.message_id = last_message_id
						LEFT JOIN {$wpdb->prefix}posts AS posts ON posts.ID = rc.listing_id
						WHERE posts.post_title LIKE %s", '%' . $q . '%' );
		} else {
			$query = "SELECT SQL_CALC_FOUND_ROWS rc.*, message, is_read, source_id, rcm.message as last_message, rcm.created_at as last_message_created_at
						FROM {$wpdb->prefix}rtcl_conversations AS rc 
						LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rcm.message_id = last_message_id";
		}

		$query .= $wpdb->prepare( " ORDER BY rc.con_id DESC LIMIT %d, %d", $offset, $per_page );

		$bdConversations = $wpdb->get_results( $query );
		$sql_posts_total = $wpdb->get_var( "SELECT FOUND_ROWS();" );
		$max_num_pages = ceil( $sql_posts_total / $per_page );

		$conversations = [];
		if ( !empty( $bdConversations ) ) {
			foreach ( $bdConversations as $conv ) {
				$convA = (array)$conv;
				$conversation = new Conversation( $convA );
				if ( $conversation->exist() ) {
					$conversations[] = wp_parse_args( $conversation->getData(), $convA );
				}
			}
		}

		return [
			'data'       => $conversations,
			'pagination' => [
				'total'        => absint( $sql_posts_total ),
				'per_page'     => $per_page,
				'current_page' => $page,
				'total_pages'  => $max_num_pages
			]
		];
	}


	static function rtcl_chat_ajax_start_conversation() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}

		if ( !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Please log in to start chatting', 'classified-listing-pro' ) ] );
		}

		$listing_id = isset( $_POST['listing_id'] ) ? absint( $_POST['listing_id'] ) : 0;
		$per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 50;
		$page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$visitor_id = get_current_user_id();
		if ( !$listing_id || !( $listing = rtcl()->factory->get_listing( $listing_id ) ) || !$listing->exists() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'No listing found to start chat.', 'classified-listing-pro' ) ] );
			return;
		}

		if ( $visitor_id === $listing->get_author_id() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'As you are an author of this listing, so you not permitted to create chat.', 'classified-listing-pro' ) ] );
		}

		$author_id = $listing->get_author_id();
		$conversation = Fns::getConversationByVisitorIdAuthorIdListingId( $visitor_id, $author_id, $listing_id );
		$response = '';
		if ( $conversation ) {
			$response = $conversation->getData( true );
			$response['messageData'] = $conversation->messages( [ 'per_page' => $per_page, 'page' => $page ] );
			$conversation->markAsRead( $visitor_id );
		}
		wp_send_json_success( $response );
	}

	/**
	 * @param $visitor_id
	 * @param $author_id
	 * @param $listing_id
	 * @return false| Conversation
	 */
	static public function has_conversation_started( $visitor_id, $author_id, $listing_id ) {
		$listing_id = empty( $listing_id ) ? get_the_ID() : $listing_id;
		$db = rtcl()->db();
		$con_table = $db->prefix . 'rtcl_conversations';
		$conversationDbData
			= $db->get_row( $db->prepare( "SELECT * FROM {$con_table} WHERE ( ( sender_id = %d AND recipient_id = %d ) OR ( sender_id = %d AND recipient_id = %d ) ) AND sender_delete = 0 AND recipient_delete = 0 AND listing_id = %d",
			$visitor_id, $author_id, $author_id, $visitor_id, $listing_id ) );
		if ( !empty( $conversation ) ) {
			$conversation = new Conversation( $conversationDbData );
			return $conversation;
		}

		return false;
	}

	static function rtcl_chat_ajax_get_messages() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		$con_id = !empty( $_POST['con_id'] ) ? absint( $_POST['con_id'] ) : 0;
		$per_page = !empty( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 50;
		$page = !empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$convData = self::_get_user_conversation_by_id( $con_id );
		if ( !$convData ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Conversation not found !!', 'classified-listing-pro' ) ] );
		}
		$conversation = new Conversation( $convData );
		Fns::update_chat_conversation_status( $con_id );
		$conversation->markAsRead( get_current_user_id() );
		wp_send_json_success( $conversation->messages( [ 'per_page' => $per_page, 'page' => $page ] ) );
	}

	static function _set_message_read( $con_id, $message_id ) {
		if ( $con_id && $message_id && $user_id = get_current_user_id() ) {
			global $wpdb;

			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}rtcl_conversation_messages SET is_read = 1 WHERE con_id = %d AND message_id = %d",
				$con_id, $message_id ) );
		}

		return false;
	}

	static function _delete_conversation( $con_id ) {
		if ( is_user_logged_in() && !empty( $con_id ) && $user_id = get_current_user_id() ) {
			global $wpdb;
			$con_ids = !is_array( $con_id ) ? [ $con_id ] : $con_id;
			$con_ids = implode( ',', $con_ids );

			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}rtcl_conversations SET sender_delete = ( CASE WHEN sender_id = %d THEN 1 ELSE sender_delete END ), recipient_delete = ( CASE WHEN recipient_id = %d THEN 1 ELSE recipient_delete END ) WHERE con_id IN ( "
				. esc_sql( $con_ids ) . " )", $user_id, $user_id ) );
		}

		return false;
	}

	static function _is_valid_conversation( $con_id ) {
		if ( $user_id = get_current_user_id() ) {
			$conversations_table = rtcl()->db()->prefix . 'rtcl_conversations';

			return rtcl()->db()->get_var( rtcl()->db()
				->prepare( "SELECT con_id FROM {$conversations_table} WHERE con_id = %d AND ( ( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 ) )",
					absint( $con_id ), $user_id, $user_id ) );
		}

		return false;
	}

	static function _get_user_conversation_by_id( $con_id ) {
		if ( $user_id = get_current_user_id() ) {
			$conversations_table = rtcl()->db()->prefix . Conversation::CONV_TABLE;

			return rtcl()->db()->get_row( rtcl()->db()
				->prepare( "SELECT * FROM {$conversations_table} WHERE con_id = %d AND ( ( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 ) )",
					absint( $con_id ), $user_id, $user_id ) );
		}

		return false;
	}

	static function rtcl_chat_ajax_send_message() {
		$tempId = !empty( $_POST['temp_id'] ) && is_numeric( $_POST['temp_id'] ) ? absint( $_POST['temp_id'] ) : '';
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [
				'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ),
				'temp_id' => $tempId,
			] );
		}
		$listing_id = !empty( $_POST['listing_id'] ) ? absint( $_POST['listing_id'] ) : 0;
		$message = !empty( $_POST['message'] ) ? Functions::clean( wp_unslash( $_POST['message'] ) ) : '';
		$rawFiles = !empty( $_FILES['files'] ) && is_array( $_FILES['files'] ) ? $_FILES['files'] : [];
		if ( !$listing_id || !( $listing = rtcl()->factory->get_listing( $listing_id ) ) || !$listing->exists() ) {
			wp_send_json_error( [
				'message' => __( 'No listing found.', 'classified-listing-pro' ),
				'temp_id' => $tempId,
			] );

			return;
		}

		$con_id = !empty( $_POST['con_id'] ) ? absint( $_POST['con_id'] ) : 0;
		$conversation = new Conversation( $con_id );
		$user_id = get_current_user_id();
		if ( !$conversation->exist() ) {
			wp_send_json_error( [
				'message' => __( 'No conversation found.', 'classified-listing-pro' ),
				'temp_id' => $tempId,
			] );
		}

		if ( $conversation->listing_id !== $listing->get_id() || ( $conversation->recipient_id !== $user_id && $conversation->sender_id !== $user_id ) ) {
			wp_send_json_error( [
				'message' => __( 'You are not permitted to access this conversation.', 'classified-listing-pro' ),
				'temp_id' => $tempId,
			] );
		}

		$response = $conversation->sent_message( [ 'text' => $message, 'files' => $rawFiles ] );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [
				'message' => $response->get_error_message(),
				'temp_id' => $tempId,
			] );
		}
		$response->temp_id = $tempId;
		if ( Fns::is_enable_chat_unread_message_email() ) {
			rtcl()->mailer()->emails['Unread_Message_Email']->trigger( $conversation, $response );
		}
		wp_send_json_success( $response );

	}


	static function rtcl_chat_ajax_visitor_send_message() {
		if ( !wp_verify_nonce( $_REQUEST[rtcl()->nonceId] ?? null, rtcl()->nonceText ) || !is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized Access !!', 'classified-listing-pro' ) ] );
		}
		$listing_id = !empty( $_POST['listing_id'] ) ? absint( $_POST['listing_id'] ) : '';
		$visitor_id = get_current_user_id();
		$tempId = !empty( $_POST['temp_id'] ) && is_numeric( $_POST['temp_id'] ) ? absint( $_POST['temp_id'] ) : '';
		if ( !$listing_id || !( $listing = rtcl()->factory->get_listing( $listing_id ) ) || !$listing->exists() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Listing not found.', 'classified-listing-pro' ) ] );
			return;
		}

		if ( $visitor_id === $listing->get_author_id() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'As you are a author of this listing, so you not permitted to create chat.', 'classified-listing-pro' ) ] );
		}

		$message = !empty( $_POST['message'] ) ? Functions::clean( wp_unslash( $_POST['message'] ) ) : '';
		$con_id = !empty( $_POST['con_id'] ) ? absint( $_POST['con_id'] ) : 0;
		$rawFiles = !empty( $_FILES['files'] ) && is_array( $_FILES['files'] ) ? $_FILES['files'] : [];
		$conversation = Fns::getConversationByVisitorIdAuthorIdListingId( $visitor_id, $listing->get_author_id(), $listing_id, $con_id );
		$messageData = [ 'text' => $message, 'files' => $rawFiles ];
		if ( !empty( $conversation ) ) {
			do_action( 'rtcl_chat_message_before_send', $messageData, $conversation, $listing );
			$response = $conversation->sent_message( $messageData );
			if ( is_wp_error( $response ) ) {
				wp_send_json_error( [
					'message' => $response->get_error_message(),
					'temp_id' => $tempId
				] );
			}
			$response->temp_id = $tempId;
			if ( Fns::is_enable_chat_unread_message_email() ) {
				rtcl()->mailer()->emails['Unread_Message_Email']->trigger( $conversation, $response );
			}
			do_action( 'rtcl_chat_message_after_send', $message, $response, $listing );
		} else {
			$sendMessageResponse = self::initiate_new_conversation_write_message( [
				'listing_id'   => $listing_id,
				'sender_id'    => $visitor_id,
				'recipient_id' => $listing->get_author_id()
			], $messageData );
			if ( is_wp_error( $sendMessageResponse ) ) {
				wp_send_json_error( [
					'message' => $sendMessageResponse->get_error_message(),
					'temp_id' => $tempId
				] );
			}
			$sendMessageResponse->temp_id = $tempId;
			$response = $sendMessageResponse;
			$conversation = new Conversation( $sendMessageResponse->con_id );
			$response->conversation = $conversation->getData();
		}
		wp_send_json_success( $response );
	}

	/**
	 * @param array $conversationData
	 * @param array $messageData {
	 * *
	 * * @type string $text The message content. Required.
	 * * @type array $files An array of uploaded files.
	 * * @type array $meta Additional metadata for the message.
	 * * @type string $type Type of the message.
	 * *                     }
	 *
	 * @return object|WP_Error
	 */
	static function initiate_new_conversation_write_message( array $conversationData, array $messageData ) {
		if ( empty( $conversationData ) ) {
			return new WP_Error( 'invalid_conversation_data', __( 'Invalid conversation data', 'classified-listing-pro' ) );
		}
		$conversation = new Conversation( $conversationData );
		if ( !$conversation->save() ) {
			return new WP_Error( 'invalid_conversation_data', __( 'Error while creating conversation', 'classified-listing-pro' ) );
		}
		do_action( 'rtcl_chat_first_message_before_send', $messageData, $conversation );
		$response = $conversation->sent_message( $messageData );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		if ( Fns::is_enable_chat_unread_message_email() ) {
			rtcl()->mailer()->emails['Unread_Message_Email']->trigger( $conversation, $response );
		}
		$pn = new PushNotification();
		$pn->notify_user( PNHelper::EVENT_CHAT, [
			'user_id' => $conversation->recipient_id,
			'object'  => $response
		] );

		$pusher = RtclPusher::getInstance();
		$pusher->trigger( [
			'chat-user-' . $conversation->sender_id,
			'chat-user-' . $conversation->recipient_id
		], 'conversation.new', [
			'conversation' => $conversation->getData()
		] );
		$pusher->trigger( [
			'chat-user-' . $conversation->sender_id,
			'chat-user-' . $conversation->recipient_id
		], 'message.new', [
			'message' => $response
		] );
		do_action( 'rtcl_chat_first_message_after_send', $response, $response );

		return $response;
	}

	public static function get_chat_count( $listing_id = 0 ) {
		$count = 0;
		if ( $listing_id ) {
			$listing = rtcl()->factory->get_listing( $listing_id );

			if ( $listing ) {
				$author_id = $listing->get_author_id();
				$db = rtcl()->db();
				$con_table = $db->prefix . 'rtcl_conversations';
				$query = $db->prepare( "SELECT count(con_id) FROM {$con_table} WHERE listing_id = %d", $listing_id );
				$count = $db->get_var( $query );
			}
		}

		return $count;
	}

}
