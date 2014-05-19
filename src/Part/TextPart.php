<?php

namespace Merr\Part;


use Merr\Header\ContentType;

class TextPart
{
	/**
	 * @var GenericPart
	 */
	private $part;

	/**
	 * @param GenericPart $part GenericPart
	 */
	public function __construct(GenericPart $part = null)
	{
		if ($part !== null) {
			$this->part = $part;

		} else {
			$this->part = new GenericPart();
		}
	}

	/**
	 * @param string $content text content
	 */
	public function setContent($content)
	{
		$this->part->setContent($content);
	}

	/**
	 * @return string text content
	 */
	public function getContent()
	{
		return $this->part->getContent();
	}

	/**
	 * @param string $type content-type
	 */
	public function setContentType($type)
	{
		$contentType = new ContentType();
		$contentType->setType($type);
		$this->part->setContentType($type);
	}

	/**
	 * @return string content-type
	 */
	public function getContentType()
	{
		return $this->part->getContentType()->getType();
	}
}