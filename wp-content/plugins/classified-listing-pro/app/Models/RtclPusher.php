<?php

namespace RtclPro\Models;

use Pusher;
use Rtcl\Helpers\Functions;
use Rtcl\Log\Logger;
use Rtcl\Traits\SingletonTrait;

class RtclPusher {
	use SingletonTrait;

	private $pusher = null;

	public function __init() {
		$chat_settings = Functions::get_option( 'rtcl_chat_settings' );
		if ( !empty( $chat_settings['pusher_enable'] ) && $chat_settings['pusher_enable'] === 'yes' && !empty( $chat_settings['pusher_app_key'] ) && !empty( $chat_settings['pusher_app_secret'] ) && !empty( $chat_settings['pusher_app_id'] ) && !empty( $chat_settings['pusher_app_cluster'] ) ) {
			try {
				$this->pusher = new Pusher\Pusher(
					$chat_settings['pusher_app_key'],
					$chat_settings['pusher_app_secret'],
					$chat_settings['pusher_app_id'],
					[ 'cluster' => $chat_settings['pusher_app_cluster'] ]
				);
			} catch ( Pusher\PusherException $err ) {
				$log = new Logger();
				$log->error( 'Pusher error: ' . $err->getMessage() );
				$this->pusher = null;
			}
		}

	}

	/**
	 * @param string|array $channel
	 * @param string $event
	 * @param array $data
	 * @return null|
	 */
	public function trigger( $channel, $event, $data ) {
		if ( $this->pusher ) {
			return $this->pusher->trigger( $channel, $event, $data );
		} else {
			return null;
		}
	}
}