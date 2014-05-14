<?php

namespace Merr\Part;


class InlineImagePart extends AbstractPart
{
	/**
	 * @var string content-disposition
	 */
	private $contentDisposition;

	/**
	 * @var string file name
	 */
	private $filename;


	/**
	 * @var string content-id
	 */
	private $contentId;

	/**
	 * @param string $contentDisposition content-disposition
	 */
	public function setContentDisposition($contentDisposition)
	{
		$this->contentDisposition = $contentDisposition;
	}

	/**
	 * @return string content-disposition
	 */
	public function getContentDisposition()
	{
		return $this->contentDisposition;
	}

	/**
	 * @param string $filename file name
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * @return string file name
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * @param string $contentId content-id
	 */
	public function setContentId($contentId)
	{
		$this->contentId = $contentId;
	}
	/**
	 * @return string content-id
	 */
	public function getContentId()
	{
		return $this->contentId;
	}
} 