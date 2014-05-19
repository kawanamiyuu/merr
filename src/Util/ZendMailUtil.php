<?php

namespace Merr\Util;

use Merr\Header\ContentDisposition;
use Merr\Header\ContentId;
use Merr\Header\ContentTransferEncoding;
use Merr\Header\ContentType;
use Merr\Part\GenericPart;
use Zend\Mail\Header\ContentTransferEncoding as ZfContentTransferEncoding;
use Zend\Mail\Header\ContentType as ZfContentType;
use Zend\Mail\Storage\Part as ZfPart;
use Zend\Mime\Decode as ZfDecode;

final class ZendMailUtil
{
	/**
	 * @param ZfPart $zfPart
	 * @return GenericPart
	 */
	public static function convertGenericPart(ZfPart $zfPart)
	{
		// Content-Type
		$contentType = new ContentType();
		if ($zfPart->getHeaders()->has("content-type")) {
			/** @var ZfContentType $zfContentType */
			$zfContentType = $zfPart->getHeader("content-type");
			$contentType->setType(strtolower($zfContentType->getType()));
			foreach ($zfContentType->getParameters() as $name => $value) {
				$contentType->addParameter(strtolower($name), $value);
			}
		}

		// Content-Transfer-Encoding
		$contentTransferEncoding = new ContentTransferEncoding();
		if ($zfPart->getHeaders()->has("content-transfer-encoding")) {
			/** @var ZfContentTransferEncoding $zfContentTransferEncoding */
			$zfContentTransferEncoding = $zfPart->getHeader("content-transfer-encoding");
			$contentTransferEncoding->setTransferEncoding(strtolower($zfContentTransferEncoding->getTransferEncoding()));
		}

		// Content
		$content = null;
		switch ($contentTransferEncoding) {
			case "base64":
				$content = base64_decode($zfPart->getContent());
				break;
			case "quoted-printable":
				$content = quoted_printable_decode($zfPart->getContent());
				break;
			default:
				$content = $zfPart->getContent();
		}
		if ($contentType->getParameter("charset") !== null) {
			$content = mb_convert_encoding($content, "UTF-8", $contentType->getParameter("charset"));
		}

		// Content-Disposition
		$contentDisposition = new ContentDisposition();
		if ($zfPart->getHeaders()->has("content-disposition")) {
			$strContentDisposition = $zfPart->getHeader("content-disposition")->getFieldValue();
			$arrContentDisposition = ZfDecode::splitHeaderField($strContentDisposition);

			$contentDisposition->setDisposition(strtolower($arrContentDisposition[0]));
			unset($arrContentDisposition[0]);
			foreach ($arrContentDisposition as $name => $value) {
				$contentDisposition->addParameter(strtolower($name), $value);
			}
		}

		// Content-Id
		$contentId = new ContentId();
		if ($zfPart->getHeaders()->has("content-id")) {
			$zfContentId = $zfPart->getHeader("content-id");
			$contentId->setId(trim($zfContentId->getFieldValue(), "<>"));
		}

		$part = new GenericPart();
		$part->setContent($content);
		$part->setContentType($contentType);
		$part->setContentTransferEncoding($contentTransferEncoding);
		$part->setContentDisposition($contentDisposition);
		$part->setContentId($contentId);

		return $part;
	}
} 