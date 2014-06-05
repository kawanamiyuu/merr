<?php

use Merr\Header\Address;
use Merr\Util\ZendMailUtil;
use Zend\Mail\Storage\Part as ZfPart;

class ZendMailUtilTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function convertAddress()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$from = ZendMailUtil::convertAddress($parts, "from");
		$this->assertCount(1, $from);
		$this->assertEquals("from-addr@example.com", $from[0]->getAddress());
		$this->assertEquals("from-name", $from[0]->getName());

		$to = ZendMailUtil::convertAddress($parts, "to");
		$this->assertCount(2, $to);
		$this->assertEquals("to-addr1@example.com", $to[0]->getAddress());
		$this->assertEquals("to-name1", $to[0]->getName());
		$this->assertEquals("to-addr2@example.com", $to[1]->getAddress());
		$this->assertEquals("to-name2", $to[1]->getName());

		$cc = ZendMailUtil::convertAddress($parts, "cc");
		$this->assertCount(0, $cc);

		$bcc = ZendMailUtil::convertAddress($parts, "bcc");
		$this->assertCount(0, $bcc);
	}

	/**
	 * @test
	 */
	public function convertDate()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$localTimeZone = "Asia/Tokyo";
		ini_set("date.timezone", $localTimeZone);

		{
			$date = ZendMailUtil::convertDate($parts);
			$this->assertInstanceOf("\\DateTime", $date);
			$this->assertEquals("2014-04-01 21:34:56", $date->format("Y-m-d H:i:s"));
			$this->assertEquals($localTimeZone, $date->getTimezone()->getName());
		}
		{
			$date = ZendMailUtil::convertDate($parts, new \DateTimeZone("UTC"));
			$this->assertInstanceOf("\\DateTime", $date);
			$this->assertEquals("2014-04-01 12:34:56", $date->format("Y-m-d H:i:s"));
			$this->assertEquals("UTC", $date->getTimezone()->getName());
		}
	}

	/**
	 * @test
	 */
	public function convertSubject()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$subject = ZendMailUtil::convertSubject($parts);
		$this->assertEquals("テストメールの件名", $subject);
	}

	/**
	 * @test
	 */
	public function convertGenericPart_textPart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$related = $parts->getPart(1);
		$alternative = $related->getPart(1);
		$plainText = $alternative->getPart(1);

		$part = ZendMailUtil::convertGenericPart($plainText);

		$this->assertNotEmpty($part->getContent());
		$this->assertEquals("text/plain", $part->getContentType()->getType());
		$this->assertEquals("ISO-2022-JP", $part->getContentType()->getParameter("charset"));
		$this->assertEquals("7bit", $part->getContentTransferEncoding()->getTransferEncoding());
		$this->assertEquals(null, $part->getContentDisposition()->getDisposition());
		$this->assertEquals(null, $part->getContentId()->getId());
	}

	/**
	 * @test
	 */
	public function convertGenericPart_inlineImagePart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$related = $parts->getPart(1);
		$inlineImagePart = $related->getPart(2);

		$part = ZendMailUtil::convertGenericPart($inlineImagePart);

		$this->assertNotEmpty($part->getContent());
		$this->assertEquals("image/png", $part->getContentType()->getType());
		$this->assertEquals("twitter.png", $part->getContentType()->getParameter("name"));
		$this->assertEquals("base64", $part->getContentTransferEncoding()->getTransferEncoding());
		$this->assertEquals("inline", $part->getContentDisposition()->getDisposition());
		$this->assertEquals("twitter.png", $part->getContentDisposition()->getParameter("filename"));
		$this->assertEquals("ii_145a82f8d1abc6fd", $part->getContentId()->getId());
	}

	/**
	 * @test
	 */
	public function convertGenericPart_attachmentPart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$attachmentPart = $parts->getPart(2);

		$part = ZendMailUtil::convertGenericPart($attachmentPart);

		$this->assertNotEmpty($part->getContent());
		$this->assertEquals("image/png", $part->getContentType()->getType());
		$this->assertEquals("google.png", $part->getContentType()->getParameter("name"));
		$this->assertEquals("base64", $part->getContentTransferEncoding()->getTransferEncoding());
		$this->assertEquals("attachment", $part->getContentDisposition()->getDisposition());
		$this->assertEquals("google.png", $part->getContentDisposition()->getParameter("filename"));
		$this->assertEquals(null, $part->getContentId()->getId());
	}

	/**
	 * @test
	 */
	public function convertGenericPartRecursively_singlepart()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$parts = ZendMailUtil::convertGenericPartRecursively($parts);
		$this->assertCount(1, $parts);
	}

	/**
	 * @test
	 */
	public function convertGenericPartRecursively_multipart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$parts = ZendMailUtil::convertGenericPartRecursively($parts);
		$this->assertCount(6, $parts);
	}
}
 