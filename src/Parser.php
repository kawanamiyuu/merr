<?php

namespace Merr;

use Zend\Mail\Storage\Part;

class Parser {

	/**
	 * @var Part parts
	 */
	private $parts;

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

	public function getHeader($name)
	{
		// TODO 実装
		return null;
	}

	public function getPlainTextPart()
	{
		// TODO 実装
		return null;
	}

	public function getHtmlTextPart()
	{
		// TODO 実装
		return null;
	}

	public function getAttachmentParts()
	{
		// TODO 実装
		return null;

	}

	public function getInlineImageParts()
	{
		// TODO 実装
		return null;
	}

	public function hasPlainTextPart()
	{
		// TODO 実装
		return false;
	}

	public function hasHtmlTextPart()
	{
		// TODO 実装
		return false;
	}

	public function hasAttachmentPart()
	{
		// TODO 実装
		return false;
	}

	public function hasInlineImagePart()
	{
		// TODO 実装
		return false;
	}
}