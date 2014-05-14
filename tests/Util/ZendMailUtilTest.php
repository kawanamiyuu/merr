<?php

use Merr\Exception\InvalidArgumentException;
use Merr\Util\ZendMailUtil;
use Zend\Mail\Storage\Part;

class ZendMailUtilTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Part
	 */
	private $parts;

	public function setUp()
	{
		$raw = getTestMail("03.htmltext_inlineimage_attachement.eml");
		$this->parts = new Part(["raw" => $raw]);
	}

	/**
	 * @test
	 */
	public function convertTextPart_ok()
	{
		$related = $this->parts->getPart(1);
		$alternative = $related->getPart(1);
		$plainText = $alternative->getPart(1);

		$textPart = ZendMailUtil::convertTextPart($plainText);

		$this->assertEquals("text/plain", $textPart->getContentType());
		$this->assertEquals("7bit", $textPart->getContentTransferEncoding());
		$this->assertEquals("iso-2022-jp", $textPart->getCharset());
		$this->assertNotEmpty($textPart->getContent());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function convertTextPart_not_text_part()
	{
		$attachment1 = $this->parts->getPart(2);

		$textPart = ZendMailUtil::convertTextPart($attachment1);
	}

	/**
	 * @test
	 */
	public function convertAttachmentPart_ok()
	{
		$attachment1 = $this->parts->getPart(2);

		$attachmentPart = ZendMailUtil::convertAttachmentPart($attachment1);

		$this->assertEquals("image/png", $attachmentPart->getContentType());
		$this->assertEquals("base64", $attachmentPart->getContentTransferEncoding());
		$this->assertEquals("attachment", $attachmentPart->getContentDisposition());
		$this->assertEquals("google.png", $attachmentPart->getFilename());
		$this->assertNotEmpty($attachmentPart->getContent());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function convertAttachmentPart_not_attachment_but_inline()
	{
		$related = $this->parts->getPart(1);
		$inline1 = $related->getPart(2);

		$attachmentPart = ZendMailUtil::convertAttachmentPart($inline1);
	}

	/**
	 * @test
	 */
	public function convertInlineImagePart_ok()
	{
		$related = $this->parts->getPart(1);
		$inline1 = $related->getPart(2);

		$inlineImagePart = ZendMailUtil::convertInlineImagePart($inline1);

		$this->assertEquals("image/png", $inlineImagePart->getContentType());
		$this->assertEquals("base64", $inlineImagePart->getContentTransferEncoding());
		$this->assertEquals("inline", $inlineImagePart->getContentDisposition());
		$this->assertEquals("ii_145a82f8d1abc6fd", $inlineImagePart->getContentId());
		$this->assertNotEmpty($inlineImagePart->getContent());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function convertInlineImagePart_not_inline_part()
	{
		$attachment1 = $this->parts->getPart(2);

		$inlineImagePart = ZendMailUtil::convertInlineImagePart($attachment1);
	}
}
 