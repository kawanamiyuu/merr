<?php

use Merr\Util\ZendMailUtil;
use Zend\Mail\Storage\Part as ZfPart;

class ZendMailUtilTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function convertHeaders()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$headers = ZendMailUtil::convertHeaders($parts);

		$this->assertCount(10, $headers);

		$this->assertEquals("1.0", $headers["mime-version"]);
		$this->assertEquals("<references1@example.com>,<references2@example.com>", $headers["references"]);
		$this->assertEquals("<in-reply-to1@example.com>,<in-reply-to2@example.com>", $headers["in-reply-to"]);
		$this->assertEquals("<message-id@localhost.localdomain>", $headers["message-id"]);
		$this->assertEquals("Tue, 01 Apr 2014 12:34:56 +0000 (UTC)", $headers["date"]);
		$this->assertEquals("from-name <from-addr@example.com>", $headers["from"]);
		$this->assertEquals("to-name1 <to-addr1@example.com>,to-name2 <to-addr2@example.com>", $headers["to"]);
		$this->assertEquals("Test Mail Subject", $headers["subject"]);
		$this->assertEquals("text/plain;charset=\"ASCII\"", $headers["content-type"]);
		$this->assertEquals("7bit", $headers["content-transfer-encoding"]);
	}

	/**
	 * @test
	 */
	public function convertAddress()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$replyTo = ZendMailUtil::convertAddress($parts, "reply-to");
		$this->assertCount(0, $replyTo);

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
	public function convertMessageId()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$messageId = ZendMailUtil::convertMessageId($parts);
		$this->assertEquals("message-id@localhost.localdomain", $messageId);
	}

	/**
	 * @test
	 */
	public function convertInReplyTo()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$inReplyTo = ZendMailUtil::convertInReplyTo($parts);
		$this->assertEquals(["in-reply-to1@example.com", "in-reply-to2@example.com"], $inReplyTo);
	}

	/**
	 * @test
	 */
	public function convertReferences()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$references = ZendMailUtil::convertReferences($parts);
		$this->assertEquals(["references1@example.com", "references2@example.com"], $references);
	}

	/**
	 * @test
	 */
	public function convertPart_textPart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$related = $parts->getPart(1);
		$alternative = $related->getPart(1);
		$plainText = $alternative->getPart(1);

		$part = ZendMailUtil::convertPart($plainText);

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
	public function convertPart_inlineImagePart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$related = $parts->getPart(1);
		$inlineImagePart = $related->getPart(2);

		$part = ZendMailUtil::convertPart($inlineImagePart);

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
	public function convertPart_attachmentPart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$attachmentPart = $parts->getPart(2);

		$part = ZendMailUtil::convertPart($attachmentPart);

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
	public function convertPartRecursively_singlepart()
	{
		$raw = getTestMail("01.plain_text_ascii.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$parts = ZendMailUtil::convertPartRecursively($parts);
		$this->assertCount(1, $parts);
	}

	/**
	 * @test
	 */
	public function convertPartRecursively_multipart()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parts = new ZfPart(["raw" => $raw]);

		$parts = ZendMailUtil::convertPartRecursively($parts);
		$this->assertCount(6, $parts);
	}
}
 