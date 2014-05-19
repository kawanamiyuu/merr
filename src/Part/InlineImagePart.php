<?php

namespace Merr\Part;


use Merr\Header\ContentDisposition;
use Merr\Header\ContentId;
use Merr\Header\ContentType;

class InlineImagePart
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
	 * @param string $content inline image content
	 */
	public function setContent($content)
	{
		$this->part->setContent($content);
	}

	/**
	 * @return string inline image content
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

	/**
	 * @param string $filename file name
	 */
	public function setFilename($filename)
	{
		$contentDisposition = new ContentDisposition();
		$contentDisposition->addParameter("filename", $filename);
		$this->part->setContentDisposition($contentDisposition);
	}

	/**
	 * @return string file name
	 */
	public function getFilename()
	{
		return $this->part->getContentDisposition()->getParameter("filename");
	}

	/**
	 * @param string $contentId content-id
	 */
	public function setContentId($id)
	{
		$contentId = new ContentId();
		$contentId->setId($id);
		$this->part->setContentId($contentId);
	}
	/**
	 * @return string content-id
	 */
	public function getContentId()
	{
		return $this->part->getContentId()->getId();
	}
} 