<?php

namespace Merr\Part;


use Merr\Header\ContentDisposition;
use Merr\Header\ContentId;
use Merr\Header\ContentTransferEncoding;
use Merr\Header\ContentType;

class GenericPart
{
	/**
	 * @var string content
	 */
	private $content;

	/**
	 * @var ContentType content-type
	 */
	private $contentType;

	/**
	 * @var ContentTransferEncoding content-transfer-encoding
	 */
	private $contentTransferEncoding;

	/**
	 * @var ContentDisposition content-disposition
	 */
	private $contentDisposition;

	/**
	 * @var ContentId content-id
	 */
	private $contentId;

	/**
	 * @param string $content content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string content
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param ContentType $contentType content-type
	 */
	public function setContentType(ContentType $contentType)
	{
		$this->contentType = $contentType;
	}

	/**
	 * @return ContentType content-type
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * @param ContentTransferEncoding $contentTransferEncoding content-transfer-encoding
	 */
	public function setContentTransferEncoding(ContentTransferEncoding $contentTransferEncoding)
	{
		$this->contentTransferEncoding = $contentTransferEncoding;
	}

	/**
	 * @return ContentTransferEncoding content-transfer-encoding
	 */
	public function getContentTransferEncoding()
	{
		return $this->contentTransferEncoding;
	}

	/**
	 * @param ContentDisposition $contentDisposition content-disposition
	 */
	public function setContentDisposition(ContentDisposition $contentDisposition)
	{
		$this->contentDisposition = $contentDisposition;
	}

	/**
	 * @return ContentDisposition content-disposition
	 */
	public function getContentDisposition()
	{
		return $this->contentDisposition;
	}

	/**
	 * @param ContentId $contentId content-id
	 */
	public function setContentId(ContentId $contentId)
	{
		$this->contentId = $contentId;
	}

	/**
	 * @return ContentId content-id
	 */
	public function getContentId()
	{
		return $this->contentId;
	}
}