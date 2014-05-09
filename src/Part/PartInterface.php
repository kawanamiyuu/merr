<?php

namespace Merr\Part;


interface PartInterface
{
	/**
	 * @param string $content content
	 */
	public function setContent($content);

	/**
	 * @return string content
	 */
	public function getContent();

	/**
	 * @param string $contentType content-type
	 */
	public function setContentType($contentType);

	/**
	 * @return string content-type
	 */
	public function getContentType();

	/**
	 * @param string $contentTransferEncoding content-transfer-encoding
	 */
	public function setContentTransferEncoding($contentTransferEncoding);

	/**
	 * @return string content-transfer-encoding
	 */
	public function getContentTransferEncoding();
} 