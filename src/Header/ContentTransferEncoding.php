<?php

namespace Merr\Header;


class ContentTransferEncoding
{
	/**
	 * @var string
	 */
	private $transferEncoding;

	/**
	 * @param string $transferEncoding content-transfer-encoding
	 */
	public function setTransferEncoding($transferEncoding)
	{
		$this->transferEncoding = $transferEncoding;
	}

	/**
	 * @return string content-transfer-encoding
	 */
	public function getTransferEncoding()
	{
		return $this->transferEncoding;
	}
}