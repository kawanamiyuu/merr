<?php

namespace Merr\Part;


abstract class AbstractPart implements PartInterface
{
	/**
	 * @var string content
	 */
	private $content;

	/**
	 * @var string content-type
	 */
	private $contentType;

	/**
	 * @var string content-transfer-encoding
	 */
	private $contentTransferEncoding;

	 /**
	  * {@inheritdoc}
	  */
	 public function setContent($content)
	 {
		 $this->content = $content;
	 }

	 /**
	  * {@inheritdoc}
	  */
	 public function getContent()
	 {
		return $this->content;
	 }

	 /**
	  * {@inheritdoc}
	  */
	 public function setContentType($contentType)
	 {
		$this->contentType = $contentType;
	 }

	 /**
	  * {@inheritdoc}
	  */
	 public function getContentType()
	 {
		return $this->contentType;
	 }

	 /**
	  * {@inheritdoc}
	  */
	 public function setContentTransferEncoding($contentTransferEncoding)
	 {
		$this->contentTransferEncoding = $contentTransferEncoding;
	 }

	 /**
	  * {@inheritdoc}
	  */
	 public function getContentTransferEncoding()
	 {
		return $this->contentTransferEncoding;
	 }
 }