<?php

use Merr\Util\ZendMailUtil;
use Zend\Mail\Storage\Part as ZfPart;

class ZendMailUtilTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var ZfPart
	 */
	private $parts;

	public function setUp()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$this->parts = new ZfPart(["raw" => $raw]);
	}

	/**
	 * @test
	 */
	public function convertGenericPart_textPart()
	{
		$related = $this->parts->getPart(1);
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
		$related = $this->parts->getPart(1);
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
		$attachmentPart = $this->parts->getPart(2);

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
 