<?php

namespace RtclPro\Models;

use WP_Error;

class Message {
	private $con_table;
	private $message_table;
	private $message_id;
	public $con_id;
	public $message;
	public $type;
	public $meta;
	public $attachments;
	public $source_id;
	public $is_read;
	public $created_at;

	function __construct( $data = [] ) {
		$this->con_table = rtcl()->db()->prefix . 'rtcl_conversations';
		$this->message_table = rtcl()->db()->prefix . 'rtcl_conversation_messages';

		if ( is_array( $data ) && !empty( $data ) ) {
			$this->setData( $data );
		} else if ( $data && is_numeric( $data ) ) {
			$this->message_id = absint( $data );
			$data = $this->get_by_id();

			if ( !empty( $data ) && is_object( $data ) ) {
				$this->setData( $data );
			}
		}
	}

	function exist() {
		return $this->message_id && $this->con_id;
	}

	function get_id() {
		return $this->message_id;
	}

	function get_conversation_id() {
		return $this->con_id;
	}


	public function getData(): object {
		$data = $this->__getData();
		$data = [ 'message_id' => $this->message_id ] + $data;

		return (object)$data;
	}

	private function setData( $raw_data = [] ) {
		$data = null;
		if ( is_array( $raw_data ) && !empty( $raw_data ) ) {
			$raw_data = wp_parse_args( $raw_data, [
				'con_id'      => isset( $raw_data['con_id'] ) ? absint( $raw_data['con_id'] ) : 0,
				'message'     => isset( $raw_data['message'] ) ? apply_filters( 'rtcl_chat_sanitize_message', $raw_data['message'] ) : 0,
				'type'        => !empty( $raw_data['type'] ) ? $raw_data['type'] : 'text',
				'attachments' => !empty( $raw_data['attachments'] ) ? ( is_array( $raw_data['attachments'] ) || is_object( $raw_data['attachments'] ) ? $raw_data['attachments'] : ( is_string( $raw_data['attachments'] ) && ( $innerMeta = json_decode( $raw_data['attachments'], true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null,
				'meta'        => !empty( $raw_data['meta'] ) ? ( is_array( $raw_data['meta'] ) || is_object( $raw_data['meta'] ) ? $raw_data['meta'] : ( is_string( $raw_data['meta'] ) && ( $innerMeta = json_decode( $raw_data['meta'], true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null,
				'source_id'   => !empty( $raw_data['source_id'] ) ? absint( $raw_data['source_id'] ) : get_current_user_id(),
				'is_read'     => !empty( $raw_data['is_read'] ) ? 1 : 0,
				'created_at'  => $raw_data['created_at'] ?? current_datetime()->format( 'Y-m-d H:i:s' ),
			] );
			$data = (object)$raw_data;
		} else if ( !empty( $raw_data ) && is_object( $raw_data ) ) {
			$data = $raw_data;
		}

		if ( $data && is_object( $data ) ) {
			$this->message_id = !empty( $data->message_id ) ? absint( $data->message_id ) : $this->message_id;
			$this->con_id = absint( $data->con_id );
			$this->message = apply_filters( 'rtcl_chat_sanitize_message', $data->message );
			$this->attachments = !empty( $data->attachments ) ? ( is_array( $data->attachments ) || is_object( $data->attachments ) ? $data->attachments : ( is_string( $data->attachments ) && ( $innerMeta = json_decode( $data->attachments, true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null;
			$this->meta = !empty( $data->meta ) ? ( is_array( $data->meta ) || is_object( $data->meta ) ? $data->meta : ( is_string( $data->meta ) && ( $innerMeta = json_decode( $data->meta, true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null;
			$this->type = $data->type;
			$this->source_id = absint( $data->source_id );
			$this->is_read = absint( $data->is_read );
			$this->created_at = $data->created_at;
		}
	}

	private function __getData(): array {
		return [
			'con_id'      => $this->con_id ?: 0,
			'source_id'   => $this->source_id ?: get_current_user_id(),
			'message'     => $this->message ? apply_filters( 'rtcl_chat_sanitize_message', $this->message ) : '',
			'meta'        => !empty( $this->meta ) ? ( is_array( $this->meta ) || is_object( $this->meta ) ? $this->meta : ( ( $innerMeta = json_decode( $this->meta, true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null,
			'attachments' => !empty( $this->attachments ) ? ( is_array( $this->attachments ) || is_object( $this->attachments ) ? $this->attachments : ( ( $innerMeta = json_decode( $this->attachments, true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null,
			'type'        => !empty( $data->type ) ? $data->type : 'text',
			'is_read'     => $this->is_read ?: 0,
			'created_at'  => $this->created_at ?: current_datetime()->format( 'Y-m-d H:i:s' )
		];
	}

	function has_unread_messages() {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$unread = rtcl()->db()->get_col( rtcl()->db()->prepare( "SELECT COUNT(message_id) FROM {$this->con_table} AS rc LEFT JOIN {$this->message_table} AS rcm ON rc.con_id = rcm.con_id WHERE ( ( sender_id = %d AND sender_delete = 0 ) OR ( recipient_id = %d AND recipient_delete = 0 ) ) AND is_read = 0 AND source_id != %d", $user_id, $user_id, $user_id ) );

			if ( $unread[0] > 0 ) {
				return $unread[0];
			}
		}
	}

	function update() {
		$data = $this->__getData();
		if ( $this->get_id() ) {
			$meta = !empty( $data['meta'] ) && ( is_array( $data['meta'] ) || is_object( $data['meta'] ) ) ? wp_json_encode( $data['meta'] ) : ( $data['meta'] && is_string( $data['meta'] ) ? $data['meta'] : null );
			$data['meta'] = !empty( $meta ) ? $meta : null;
			return rtcl()->db()->update(
				"{$this->message_table}",
				$data,
				[
					'message_id' => $this->get_id()
				]
			);
		}

		return false;
	}

	/**
	 * @return int|WP_Error
	 */
	public function save() {
		$data = $this->__getData();

		if ( empty( $data['con_id'] ) ) {
			return new WP_Error( 'rtcl_chat_error', __( 'Conversation ID , message oe meta is required', 'classified-listing-pro' ) );
		}

		if ( empty( $data['message'] ) && empty( $data['attachments'] ) ) {
			return new WP_Error( 'rtcl_chat_error', __( 'Message or Attachment is required.', 'classified-listing-pro' ) );
		}
		$data['attachments'] = !empty( $data['attachments'] ) ? ( is_array( $data['attachments'] ) || is_object( $data['attachments'] ) ? wp_json_encode( $data['attachments'] ) : ( ( $innerMeta = json_decode( $data['attachments'], true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null;
		$data['meta'] = !empty( $data['meta'] ) ? ( is_array( $data['meta'] ) || is_object( $data['meta'] ) ? wp_json_encode( $data['meta'] ) : ( ( $innerMeta = json_decode( $data['meta'], true ) ) && json_last_error() === JSON_ERROR_NONE ? $innerMeta : null ) ) : null;

		if ( empty( $data['type'] ) || !empty( $data['attachments'] ) ) {
			$data['type'] = 'attachment';
		}
		$result = rtcl()->db()->insert(
			$this->message_table,
			$data,
			[
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s'
			]
		);

		if ( !$result && rtcl()->db()->last_error ) {
			return new WP_Error( 'rtcl_chat_error', rtcl()->db()->last_error );
		}
		$this->message_id = rtcl()->db()->insert_id;
		$this->setData( $data );

		return $this->message_id;
	}

	private function get_by_id() {
		if ( $this->get_id() ) {
			return rtcl()->db()->get_row( rtcl()->db()->prepare( "SELECT * FROM {$this->message_table} WHERE message_id = %d", $this->get_id() ) );
		}
	}

	public function conversation() {
		if ( $this->exist() ) {
			return new Conversation( $this->con_id );
		}

		return null;
	}


}