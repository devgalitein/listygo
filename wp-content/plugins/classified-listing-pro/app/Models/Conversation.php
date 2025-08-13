<?php


namespace RtclPro\Models;

use Rtcl\Helpers\Functions;
use RtclPro\Controllers\ChatController;
use RtclPro\Helpers\Fns;
use RtclPro\Helpers\PNHelper;
use WP_Error;

class Conversation {

	const CONV_TABLE = 'rtcl_conversations';
	const CONV_MESSAGE_TABLE = 'rtcl_conversation_messages';

	private $con_table;
	private $message_table;
	private $con_id;
	public $listing_id;
	public $sender_id;
	public $recipient_id;
	public $created_at;
	public $updated_at;
	public $last_message_id = 0;
	public $sender_review = 0;
	public $recipient_review = 0;
	public $invert_review = 0;
	public $sender_delete = 0;
	public $recipient_delete = 0;


	function __construct( $data = [] ) {
		$this->con_table = rtcl()->db()->prefix . self::CONV_TABLE;
		$this->message_table = rtcl()->db()->prefix . self::CONV_MESSAGE_TABLE;

		if ( is_array( $data ) && !empty( $data ) ) {
			$this->setData( $data );
		} else if ( is_object( $data ) && !empty( $data->con_id ) ) {
			$this->con_id = absint( $data->con_id );
			$this->setData( (array)$data );
		} else if ( $data && is_int( $data ) ) {
			$this->con_id = absint( $data );
			$this->setById();
		}

	}

	public function get_id() {
		return absint( $this->con_id );
	}

	public function exist() {
		return $this->con_id && $this->listing_id;
	}

	/**
	 * @param boolean $asArray
	 *
	 * @return array|object
	 */
	public function getData( bool $asArray = false ) {
		$data = $this->__getData();
		$data = [
				'con_id'       => $this->con_id,
				'listing'      => $this->get_listing(),
				'unread_count' => 0,
				'sender'       => $this->get_sender(),
				'recipient'    => $this->get_recipient(),
			] + $data;
		$user_id = get_current_user_id();
		global $wpdb;
		$unread = $wpdb->get_col( $wpdb->prepare( "SELECT COUNT(message_id) FROM {$wpdb->prefix}rtcl_conversations AS rc LEFT JOIN {$wpdb->prefix}rtcl_conversation_messages AS rcm ON rc.con_id = rcm.con_id WHERE ( ( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 ) ) AND is_read = 0 AND source_id != %d AND rc.con_id = %d",
			$user_id, $user_id, $user_id, $this->con_id ) );
		$data['unread_count'] = !empty( $unread[0] ) ? $unread[0] : 0;
		if ( $this->last_message_id ) {
			$resMessage = rtcl()->db()->get_row( rtcl()->db()->prepare( "SELECT * FROM {$this->message_table} WHERE message_id = %d", $this->last_message_id ) );
			if ( $resMessage ) {
				$data['last_message'] = apply_filters( 'rtcl_chat_sanitize_message', $resMessage->message );
				$data['last_message_created_at'] = $resMessage->created_at;
			}
		}
		if ( $asArray ) {
			return $data;
		}

		return (object)$data;
	}

	private function setData( $raw_data = [] ) {
		if ( is_array( $raw_data ) && !empty( $raw_data ) ) {
			$raw_data = wp_parse_args( $raw_data, [
				'listing_id'       => isset( $raw_data['listing_id'] ) ? absint( $raw_data['listing_id'] ) : 0,
				'sender_id'        => isset( $raw_data['sender_id'] ) ? absint( $raw_data['sender_id'] ) : 0,
				'recipient_id'     => isset( $raw_data['recipient_id'] ) ? absint( $raw_data['recipient_id'] ) : 0,
				'sender_delete'    => isset( $raw_data['sender_delete'] ) ? boolval( $raw_data['sender_delete'] ) : 0,
				'recipient_delete' => isset( $raw_data['recipient_delete'] ) ? boolval( $raw_data['recipient_delete'] ) : 0,
				'last_message_id'  => isset( $raw_data['last_message_id'] ) ? absint( $raw_data['last_message_id'] ) : 0,
				'sender_review'    => isset( $raw_data['sender_review'] ) ? boolval( $raw_data['sender_review'] ) : 0,
				'recipient_review' => isset( $raw_data['recipient_review'] ) ? boolval( $raw_data['recipient_review'] ) : 0,
				'invert_review'    => isset( $raw_data['invert_review'] ) ? boolval( $raw_data['invert_review'] ) : 0,
			] );
			$data = (object)$raw_data;

		} else {
			$data = is_object( $raw_data ) && !empty( $raw_data ) ? $raw_data : null;
		}
		if ( $data && is_object( $data ) ) {
			$this->con_id = !empty( $data->con_id ) ? absint( $data->con_id ) : $this->con_id;
			$this->listing_id = absint( $data->listing_id );
			$this->sender_id = absint( $data->sender_id );
			$this->recipient_id = absint( $data->recipient_id );
			$this->sender_delete = $data->sender_delete;
			$this->recipient_delete = $data->recipient_delete;
			$this->last_message_id = absint( $data->last_message_id );
			$this->sender_review = $data->sender_review;
			$this->recipient_review = $data->recipient_review;
			$this->invert_review = $data->invert_review;
			$this->created_at = $data->created_at;
			$this->updated_at = $data->updated_at;
		}
	}

	private function __getData(): array {
		return [
			'listing_id'       => absint( $this->listing_id ),
			'sender_id'        => absint( $this->sender_id ),
			'recipient_id'     => absint( $this->recipient_id ),
			'sender_delete'    => boolval( $this->sender_delete ),
			'recipient_delete' => boolval( $this->recipient_delete ),
			'last_message_id'  => absint( $this->last_message_id ),
			'sender_review'    => boolval( $this->sender_review ),
			'recipient_review' => boolval( $this->recipient_review ),
			'invert_review'    => boolval( $this->invert_review ),
			'created_at'       => $this->created_at ?? current_datetime()->format( 'Y-m-d H:i:s' ),
			'updated_at'       => $this->updated_at ?? current_datetime()->format( 'Y-m-d H:i:s' ),
		];
	}

	public function get_listing() {
		if ( !empty( $this->listing_id ) && ( $listing = rtcl()->factory->get_listing( $this->listing_id ) ) ) {
			return [
				'id'        => absint( $this->listing_id ),
				'title'     => $listing->get_the_title(),
				'url'       => $listing->get_the_permalink(),
				'images'    => Functions::get_listing_images( $this->listing_id ),
				'amount'    => $listing->get_price_html(),
				'raw_price' => $listing->get_price(),
				'location'  => $listing->get_locations(),
				'category'  => $listing->get_categories(),
			];
		}

		return null;
	}

	public function get_sender() {
		if ( !empty( $this->sender_id ) ) {
			$user_details = get_userdata( $this->sender_id );
			if ( !empty( $user_details ) ) {
				$pp_id = absint( get_user_meta( $user_details->ID, '_rtcl_pp_id', true ) );
				$image_url = $pp_id ? wp_get_attachment_image_url( $pp_id ) : get_avatar_url( $user_details->ID );

				return [
					'id'              => $user_details->ID,
					'name'            => $user_details->display_name,
					'email'           => $user_details->user_email,
					'phone'           => get_user_meta( $user_details->ID, '_rtcl_phone', true ),
					'whatsapp'        => get_user_meta( $user_details->ID, '_rtcl_whatsapp_number', true ),
					'website'         => get_user_meta( $user_details->ID, '_rtcl_website', true ),
					'profile_picture' => $image_url,
				];
			}
		}

		return null;
	}

	public function get_recipient() {
		if ( !empty( $this->recipient_id ) ) {
			$user_details = get_userdata( $this->recipient_id );
			if ( !empty( $user_details ) ) {
				$pp_id = absint( get_user_meta( $user_details->ID, '_rtcl_pp_id', true ) );
				$image_url = $pp_id ? wp_get_attachment_image_url( $pp_id ) : get_avatar_url( $user_details->ID );

				return [
					'id'              => $user_details->ID,
					'name'            => $user_details->display_name,
					'email'           => $user_details->user_email,
					'phone'           => get_user_meta( $user_details->ID, '_rtcl_phone', true ),
					'whatsapp'        => get_user_meta( $user_details->ID, '_rtcl_whatsapp_number', true ),
					'website'         => get_user_meta( $user_details->ID, '_rtcl_website', true ),
					'profile_picture' => $image_url,
				];
			}
		}

		return null;
	}

	/**
	 * Sends a message using provided options.
	 *
	 * @param array $args {
	 *
	 * @type string $text The message content. Required.
	 * @type array $files An array of uploaded files.
	 * @type array $meta Additional metadata for the message.
	 * @type string $type Type of the message.
	 *                     }
	 *
	 * @return object|WP_Error
	 */
	function sent_message( array $args ) {

		$text = $args['text'] ?? '';
		$rawFiles = $args['files'] ?? [];
		$meta = $args['meta'] ?? [];
		$type = !empty( $args['type'] ) && in_array( $args['type'], [ 'text', 'attachment', 'system' ] ) ? $args['type'] : '';


		if ( $text ) {
			$bad_words = Functions::get_option_item( 'rtcl_chat_settings', 'bad_words' );
			$bad_word_list = $bad_words ? explode( ',', $bad_words ) : [];
			if ( !empty( $bad_word_list ) ) {
				$text = Fns::filter_bad_words( $text, $bad_word_list );
				if ( is_wp_error( $text ) ) {
					return $text;
				}
			}
		}

		// Check if a file was uploaded
		$files = self::uploadChatFilesConvertToMetas( $rawFiles );
		if ( empty( $text ) && empty( $files ) ) {
			return new WP_Error( 'empty_message', __( 'No message or attach a file to send message.', 'classified-listing-pro' ) );
		}
		$meta = ( is_array( $meta ) || is_object( $meta ) ) && !empty( $meta ) ? $meta : null;
		if ( empty( $type ) ) {
			$type = !empty( $files ) ? 'attachment' : 'text';
		}
		$message = new Message();
		$message->type = $type;
		$message->con_id = $this->get_id();
		$message->message = $text;
		if ( !empty( $files ) ) {
			$message->attachments = $files;
		}
		if ( !empty( $meta ) ) {
			$message->meta = $meta;
		}
		$messageSaved = $message->save();
		if ( is_wp_error( $messageSaved ) ) {
			return $messageSaved;
		}
		$this->last_message_id = $message->get_id();
		$this->update();
		$dataObject = $message->getData();

		$pn = new PushNotification();
		$pn->notify_user( PNHelper::EVENT_CHAT, [
			'object' => $dataObject
		] );
		$pusher = RtclPusher::getInstance();
		$receiverUserId = $dataObject->source_id == $this->sender_id ? $this->recipient_id : $this->sender_id;
		$pusher->trigger( [ 'chat-user-' . $receiverUserId ], 'message.new', [
			'message' => $dataObject
		] );

		$this->notifyUnreadCount( $receiverUserId, $this );

		return $dataObject;
	}


	/**
	 * @param integer $visitor_id
	 * @param integer $author_id
	 * @param integer $listing_id
	 *
	 * @return object|null conversionDB
	 */
	function has_started( int $visitor_id, int $author_id, int $listing_id ) {
		$listing_id = empty( $listing_id ) ? get_the_ID() : $listing_id;

		$obj = rtcl()->db()->get_row( rtcl()->db()->prepare( "SELECT con_id FROM {$this->con_table} WHERE ( ( sender_id = %d AND recipient_id = %d ) OR ( sender_id = %d AND recipient_id = %d ) ) AND sender_delete = 0 AND recipient_delete = 0 AND listing_id = %d", $visitor_id, $author_id, $author_id, $visitor_id, $listing_id ) );
		if ( !empty( $obj ) ) {
			return $obj;
		}

		return null;
	}

	/**
	 * @return array|object|void|null
	 */
	private function setById() {
		if ( $this->get_id() ) {
			$obj = rtcl()->db()->get_row( rtcl()->db()->prepare( "SELECT * FROM {$this->con_table} WHERE con_id = %d", $this->get_id() ) );
			if ( $obj ) {
				$this->setData( $obj );
			} else {
				$this->con_id = null;
			}
		}
	}

	/**
	 * @param integer $visitor_user
	 * @return bool|int
	 */
	public function markAsRead( int $visitor_user ) {
		$source_id = $this->sender_id == $visitor_user ? $this->recipient_id : ( $this->recipient_id == $visitor_user ? $this->sender_id : 0 );
		if ( !$source_id ) {
			return false;
		}
		rtcl()->db()->update(
			$this->message_table,
			[ 'is_read' => 1 ],
			[
				'con_id'    => $this->get_id(),
				'source_id' => $source_id,
			]
		);
		$this->notifyUnreadCount( $visitor_user, $this );
		return true;
	}

	/**
	 * @param int $user_id
	 * @return void
	 */
	public static function notifyUnreadCount( int $user_id, Conversation $conversation = null ) {
		$pusher = RtclPusher::getInstance();
		$pusher->trigger( [ 'chat-user-' . $user_id ], 'conversation.unread', [
			'user_id'         => $user_id,
			'count'           => ChatController::has_unread_messages( $user_id ),
			'conversation_id' => $conversation ? $conversation->get_id() : 0,
		] );
	}

	/**
	 * @param array $data
	 *
	 * @return array ['data'=>[], 'pagination'=>[]]
	 */
	public function messages( $data = [] ) {
		$data = is_array( $data ) ? $data : ( is_int( $data ) ? [ 'per_page' => $data ] : [] );
		$data = wp_parse_args( $data, [
			'per_page' => 50,
			'page'     => 1,
			'q'        => '',
		] );
		$per_page = absint( $data['per_page'] );
		$per_page = $per_page ?: 50;
		$page = absint( $data['page'] );
		$page = $page ?: 1;
		$offset = ( $page - 1 ) * $per_page;
		global $wpdb;
		$total_messages = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->message_table} WHERE con_id = %d",
			$this->get_id()
		) );
		$messages = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->message_table} WHERE con_id = %d ORDER BY `created_at` DESC LIMIT %d, %d", $this->get_id(), $offset, $per_page ) );
		$max_num_pages = ceil( $total_messages / $per_page );

		return [
			'data'       => $messages,
			'pagination' => [
				'total'        => absint( $total_messages ),
				'per_page'     => $per_page,
				'current_page' => $page,
				'total_pages'  => $max_num_pages
			]
		];
	}

	public function update() {
		$data = $this->__getData();
		if ( $this->get_id() && $data['listing_id'] && $data['sender_id'] && $data['recipient_id'] ) {
			$data['updated_at'] = current_datetime()->format( 'Y-m-d H:i:s' );

			return rtcl()->db()->update(
				$this->con_table,
				$data,
				[
					'con_id' => $this->get_id()
				]
			);
		}

		return false;
	}

	public function save() {
		$data = $this->__getData();

		if ( $data['listing_id'] && $data['sender_id'] && $data['recipient_id'] ) {
			$existing = $this->has_started( $data['sender_id'], $data['recipient_id'], $data['listing_id'] );
			if ( !$existing ) {
				$result = rtcl()->db()->insert(
					$this->con_table,
					$data,
					[
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s'
					]
				);

				if ( $result ) {
					$this->con_id = rtcl()->db()->insert_id;
					$this->setData( (object)$data );

					return $this->con_id;
				}

			}
		}

		return false;
	}


	/**
	 * @param array $rawFiles
	 *
	 * @return array
	 */
	public static function uploadChatFilesConvertToMetas( array $rawFiles = [] ): array {
		$files = [];
		if ( !empty( $rawFiles ) ) {
			$config = Fns::getChatAttachmentConfig();
			$allowedFileTypes = array_merge( $config['image_types'], $config['file_types'] );
			$max_size_bytes = $config['max_size_mb'] * 1024 * 1024;
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			foreach ( $rawFiles['name'] as $index => $filetName ) {
				if ( $rawFiles['error'][$index] !== UPLOAD_ERR_OK ) {
					continue; // Skip files with errors
				}

				$file = [
					'name'     => $rawFiles['name'][$index],
					'type'     => $rawFiles['type'][$index],
					'tmp_name' => $rawFiles['tmp_name'][$index],
					'error'    => $rawFiles['error'][$index],
					'size'     => $rawFiles['size'][$index]
				];
				// Validate type
				$parts = explode( '/', $file['type'] );
				$mimeSubtype = $parts[1] ?? null;

				if ( !in_array( $mimeSubtype, $allowedFileTypes ) ) {
					continue;
				}
				if ( $file['size'] > $max_size_bytes ) {
					continue;
				}
				// Upload file
				$upload = wp_handle_upload( $file, [ 'test_form' => false ] );
				if ( !isset( $upload['error'] ) ) {
					$files[] = [
						'url'      => $upload['url'],
						'type'     => Fns::getFileType( $upload['type'] ),
						'mimeType' => $upload['type'],
						'name'     => Functions::clean( wp_unslash( $file['name'] ) ),
						'size'     => filesize( $upload['file'] ),
					];
				}

			}
		}
		return $files;
	}
}