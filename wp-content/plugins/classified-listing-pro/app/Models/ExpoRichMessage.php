<?php

namespace RtclPro\Models;

use ExpoSDK\ExpoMessage;

class ExpoRichMessage extends ExpoMessage{
	/** @var array|null */
	protected $richContent;

	public function setRichContent(array $content): self {
		$this->richContent = $content;
		return $this;
	}
}