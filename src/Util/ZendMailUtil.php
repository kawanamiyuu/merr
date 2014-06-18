<?php

namespace Merr\Util;

use Merr\Exception\InvalidArgumentException;
use Merr\Header\Address;
use Merr\Header\ContentDisposition;
use Merr\Header\ContentId;
use Merr\Header\ContentTransferEncoding;
use Merr\Header\ContentType;
use Merr\Part\Part;
use Zend\Mail\Address as ZfAddress;
use Zend\Mail\Header\AbstractAddressList as ZfAbstractAddressList;
use Zend\Mail\Header\ContentTransferEncoding as ZfContentTransferEncoding;
use Zend\Mail\Header\ContentType as ZfContentType;
use Zend\Mail\Header\Date as ZfDate;
use Zend\Mail\Header\HeaderInterface as ZfHeaderInterface;
use Zend\Mail\Storage\Part as ZfPart;
use Zend\Mime\Decode as ZfDecode;

final class ZendMailUtil
{
	/**
	 * @param ZfPart $zfPart
	 * @return array
	 */
	public static function convertHeaders(ZfPart $zfPart)
	{
		$headers = [];

		foreach ($zfPart->getHeaders() as $zfHeader) {
			/** @var ZfHeaderInterface $zfHeader */
			$name = strtolower($zfHeader->getFieldName());
			$value = $zfHeader->getFieldValue();
			// TODO ヘッダー中の改行の扱いを統一したい→半角スペース1つに置換したい
			// Zend\Mail\Headerで定義されているヘッダーと定義れていないヘッダー、
			// また、定義されているヘッダーでもヘッダー毎に改行の扱いが異なる。
			$value = preg_replace('/\r?\n[ \t]+/', '', $value);
			$headers[$name] = $value;
		}

		return $headers;
	}

	/**
	 * @param ZfPart $zfPart
	 * @param string $fieldName
	 * @return Address[]
	 */
	public static function convertAddress(ZfPart $zfPart, $fieldName)
	{
		$addresses = [];
		if ($zfPart->getHeaders()->has($fieldName)) {
			/** @var ZfAbstractAddressList $zfAddressList */
			$zfAddressList = $zfPart->getHeaders()->get($fieldName);
			foreach ($zfAddressList->getAddressList() as $zfAddress) {
				/** @var ZfAddress $zfAddress */
				$addresses[] = new Address($zfAddress->getEmail(), $zfAddress->getName());
			}
		}

		return $addresses;
	}

	/**
	 * @param ZfPart        $zfPart
	 * @param \DateTimeZone $dateTimeZone
	 * @return \DateTime|null
	 */
	public static function convertDate(ZfPart $zfPart, \DateTimeZone $dateTimeZone = null)
	{
		if ($zfPart->getHeaders()->has("date")) {
			/** @var ZfDate $zfDate */
			$zfDate = $zfPart->getHeaders()->get("date");
			$timestamp = strtotime($zfDate->getFieldValue());

			$dateTime = new \DateTime();
			$dateTime->setTimestamp($timestamp);
			if ($dateTimeZone !== null) {
				$dateTime->setTimezone($dateTimeZone);
			}

			return $dateTime;
		}

		return null;
	}

	/**
	 * @param ZfPart $zfPart
	 * @return null|string
	 */
	public static function convertSubject(ZfPart $zfPart)
	{
		if ($zfPart->getHeaders()->has("subject")) {
			return $zfPart->getHeaders()->get("subject")->getFieldValue();
		}

		return null;
	}

	/**
	 * @param ZfPart $zfPart
	 * @param string $fieldName
	 * @return string[]
	 */
	public static function convertIdentificationField(ZfPart $zfPart, $fieldName)
	{
		if ($zfPart->getHeaders()->has($fieldName)) {
			$str = $zfPart->getHeaders()->get($fieldName)->getFieldValue();
			$arr = explode(',', $str);
			array_walk($arr, function(&$val) {
				$val = trim($val, "<>");
			});
			return $arr;
		}

		return [];
	}

	/**
	 * @param ZfPart $zfPart
	 * @return null|string
	 */
	public static function convertMessageId(ZfPart $zfPart)
	{
		$result = self::convertIdentificationField($zfPart, "message-id");
		return count($result) > 0 ? $result[0] : null;
	}

	/**
	 * @param ZfPart $zfPart
	 * @return string[]
	 */
	public static function convertInReplyTo(ZfPart $zfPart)
	{
		return self::convertIdentificationField($zfPart, "in-reply-to");
	}

	/**
	 * @param ZfPart $zfPart
	 * @return string[]
	 */
	public static function convertReferences(ZfPart $zfPart)
	{
		return self::convertIdentificationField($zfPart, "references");
	}

	/**
	 * @param ZfPart $zfPart
	 * @return Part
	 */
	public static function convertPart(ZfPart $zfPart)
	{
		if ($zfPart->isMultipart()) {
			throw new InvalidArgumentException("this part is multipart.");
		}

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

		$part = new Part();
		$part->setContent($content);
		$part->setContentType($contentType);
		$part->setContentTransferEncoding($contentTransferEncoding);
		$part->setContentDisposition($contentDisposition);
		$part->setContentId($contentId);

		return $part;
	}

	/**
	 * @param ZfPart $zfPart
	 * @return Part[]
	 */
	public static function convertPartRecursively(ZfPart $zfPart)
	{
		$parts = [];
		if ($zfPart->isMultipart()) {
			$rii = new \RecursiveIteratorIterator($zfPart, \RecursiveIteratorIterator::LEAVES_ONLY);
			foreach ($rii as $part) {
				$parts[] = self::convertPart($part);
			}
		} else {
			$parts[] = self::convertPart($zfPart);
		}

		return $parts;
	}
} 