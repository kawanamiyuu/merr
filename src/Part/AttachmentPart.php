<?php

namespace Merr\Part;


class AttachmentPart extends AbstractPart
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
} 