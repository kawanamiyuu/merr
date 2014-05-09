<?php

namespace Merr;

use Merr\Part\AttachmentPart;
use Merr\Part\InlineImagePart;
use Merr\Part\TextPart;
use Zend\Mail\Storage\Part;

class Parser
{
	/**
	 * Constructor
	 *
	 * @param string $rawMessage raw message
	 */
	public function __construct($rawMessage)
	{
		// TODO リソース型も許容する？
		if (!is_string($rawMessage)) {
			throw new \InvalidArgumentException("argument must be string.");
		}

		$parts = new Part(["raw" => $rawMessage]);
	}

	/**
	 * @param string $fieldName header field name
	 * @return mixed
	 */
	public function getHeader($fieldName)
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return TextPart text/plain part
	 */
	public function getPlainTextPart()
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return TextPart text/html part
	 */
	public function getHtmlTextPart()
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return AttachmentPart[] array of attachment part
	 */
	public function getAttachmentParts()
	{
		// TODO 実装
		return null;

	}

	/**
	 * @return InlineImagePart[] array of inline image part
	 */
	public function getInlineImageParts()
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return bool whether this message has text/plain part
	 */
	public function hasPlainTextPart()
	{
		// TODO 実装
		return false;
	}

	/**
	 * @return bool whether this message has text/html part
	 */
	public function hasHtmlTextPart()
	{
		// TODO 実装
		return false;
	}

	/**
	 * @return bool whether this message has attachment part
	 */
	public function hasAttachmentPart()
	{
		// TODO 実装
		return false;
	}

	/**
	 * @return bool whether this message has inline image part
	 */
	public function hasInlineImagePart()
	{
		// TODO 実装
		return false;
	}
}