<?php

namespace Merr\Util;

use Merr\Exception\InvalidArgumentException;
use Merr\Part\AttachmentPart;
use Merr\Part\InlineImagePart;
use Merr\Part\TextPart;
use Zend\Mail\Header\ContentTransferEncoding;
use Zend\Mail\Header\ContentType;
use Zend\Mail\Storage\Part;

final class ZendMailUtil
{
	/**
	 * @param Part $part text part
	 * @return TextPart
	 */
	public static function convertTextPart(Part $part)
	{
		/** @var ContentType $contentType */
		$contentType = $part->getHeader("content-type");
		$type = strtolower($contentType->getType());

		list($mainType, ) = explode("/", $type);
		if ($mainType !== "text") {
			throw new InvalidArgumentException("this is not text/* part. (actual: " . $type . ")");
		}

		/** @var ContentTransferEncoding $contentTransferEncoding */
		$contentTransferEncoding = $part->getHeader("content-transfer-encoding");
		$transferEncoding = strtolower($contentTransferEncoding->getTransferEncoding());

		$charset = strtolower($contentType->getParameter("charset"));

		$content = $part->getContent();
		if ($transferEncoding === "base64") {
			$content = base64_decode($content);
		} else if ($transferEncoding === "quoted-printable") {
			$content = quoted_printable_decode($content);
		}
		$content = mb_convert_encoding($content, "UTF-8", $charset);

		$textPart = new TextPart();
		$textPart->setContent($content);
		$textPart->setContentType($type);
		$textPart->setContentTransferEncoding($transferEncoding);
		$textPart->setCharset($charset);

		return $textPart;
	}

	/**
	 * @param Part $part attachment part
	 * @return AttachmentPart
	 */
	public static function convertAttachmentPart(Part $part)
	{
		$contentDisposition = $part->getHeader("content-disposition")->getFieldValue();
		// TODO Zend\Mine\Decode::splitHeaderField が使えそう
		list($disposition, $filename) = explode(";", $contentDisposition);
		$disposition = strtolower($disposition);
		// TODO content-typeのnameパラメータの考慮
		$filename = trim(explode("=", $filename)[1], " \"'");

		if ($disposition === "inline") {
			throw new InvalidArgumentException("this is inline image part.");
		}

		/** @var ContentType $contentType */
		$contentType = $part->getHeader("content-type");
		$type = strtolower($contentType->getType());

		/** @var ContentTransferEncoding $contentTransferEncoding */
		$contentTransferEncoding = $part->getHeader("content-transfer-encoding");
		$transferEncoding = strtolower($contentTransferEncoding->getTransferEncoding());

		$content = $part->getContent();
		if ($transferEncoding === "base64") {
			$content = base64_decode($content);
		} else if ($transferEncoding === "quoted-printable") {
			$content = quoted_printable_decode($content);
		}

		$attachmentPart = new AttachmentPart();
		$attachmentPart->setContent($content);
		$attachmentPart->setContentType($type);
		$attachmentPart->setContentTransferEncoding($transferEncoding);
		$attachmentPart->setContentDisposition($disposition);
		$attachmentPart->setFilename($filename);

		return $attachmentPart;
	}

	/**
	 * @param Part $part inline image part
	 * @return InlineImagePart
	 */
	public static function convertInlineImagePart(Part $part)
	{
		$contentDisposition = $part->getHeader("content-disposition")->getFieldValue();
		// TODO Zend\Mine\Decode::splitHeaderField が使えそう
		list($disposition, $filename) = explode(";", $contentDisposition);
		$disposition = strtolower($disposition);
		// TODO content-typeのnameパラメータの考慮
		$filename = trim(explode("=", $filename)[1], " \"'");

		if ($disposition !== "inline") {
			throw new InvalidArgumentException("this is not inline image part. (actual: " . $disposition . ")");
		}

		/** @var ContentType $contentType */
		$contentType = $part->getHeader("content-type");
		$type = strtolower($contentType->getType());

		/** @var ContentTransferEncoding $contentTransferEncoding */
		$contentTransferEncoding = $part->getHeader("content-transfer-encoding");
		$transferEncoding = strtolower($contentTransferEncoding->getTransferEncoding());

		$content = $part->getContent();
		if ($transferEncoding === "base64") {
			$content = base64_decode($content);
		} else if ($transferEncoding === "quoted-printable") {
			$content = quoted_printable_decode($content);
		}

		$contentId = $part->getHeader("content-id")->getFieldValue();
		$contentId = trim($contentId, "<>");

		$inlineImagePart = new InlineImagePart();
		$inlineImagePart->setContent($content);
		$inlineImagePart->setContentType($type);
		$inlineImagePart->setContentTransferEncoding($transferEncoding);
		$inlineImagePart->setContentDisposition($disposition);
		$inlineImagePart->setFilename($filename);
		$inlineImagePart->setContentId($contentId);

		return $inlineImagePart;
	}
} 