<?php

use Merr\Parser;
use Merr\Part\AttachmentPart;
use Merr\Part\Part;
use Merr\Part\TextPart;

class ParserTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Parser
	 */
	private $parser;

	public function setUp()
	{
		$mail = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$this->parser = new Parser($mail);
	}

	/**
	 * @test
	 */
	public function getParts_no_callbak()
	{
		$parts = $this->parser->getParts();
		$this->assertCount(6, $parts);
		$this->assertCount(0, $this->parser->getParts());
	}

	/**
	 * @test
	 */
	public function getParts_callback_without_custom_part()
	{
		$plainTexts = $this->parser->getParts(function(Part $part) {
			return $part->getContentType()->getType() === "text/plain";
		});

		$this->assertCount(1, $plainTexts);
		$this->assertInstanceOf("Merr\\Part\\Part", $plainTexts[0]);
		$this->assertEquals("text/plain", $plainTexts[0]->getContentType()->getType());

		$attachments = $this->parser->getParts(function(Part $part) {
			return $part->getContentDisposition()->getDisposition() === "attachment";
		});

		$this->assertCount(2, $attachments);
		$this->assertInstanceOf("Merr\\Part\\Part", $attachments[0]);
		$this->assertEquals("google.png", $attachments[0]->getContentDisposition()->getParameter("filename"));
		$this->assertInstanceOf("Merr\\Part\\Part", $attachments[1]);
		$this->assertEquals("blogger.png", $attachments[1]->getContentDisposition()->getParameter("filename"));

		$this->assertCount(3, $this->parser->getParts());
		$this->assertCount(0, $this->parser->getParts());
	}

	/**
	 * @test
	 */
	public function getParts_callback_with_custom_part()
	{
		/** @var TextPart[] $plainTexts */
		$plainTexts = $this->parser->getParts(function(Part $part) {
			return $part->getContentType()->getType() === "text/plain";
		}, new TextPart);

		$this->assertCount(1, $plainTexts);
		$this->assertInstanceOf("Merr\\Part\\TextPart", $plainTexts[0]);
		$this->assertEquals("text/plain", $plainTexts[0]->getContentType());

		/** @var AttachmentPart[] $attachments */
		$attachments = $this->parser->getParts(function(Part $part) {
			return $part->getContentDisposition()->getDisposition() === "attachment";
		}, new AttachmentPart);

		$this->assertCount(2, $attachments);
		$this->assertInstanceOf("Merr\\Part\\AttachmentPart", $attachments[0]);
		$this->assertEquals("google.png", $attachments[0]->getFilename());
		$this->assertInstanceOf("Merr\\Part\\AttachmentPart", $attachments[1]);
		$this->assertEquals("blogger.png", $attachments[1]->getFilename());

		$this->assertCount(3, $this->parser->getParts());
		$this->assertCount(0, $this->parser->getParts());
	}

	/**
	 * @test
	 */
	public function getPlainTextParts()
	{
		/** @var TextPart[] $plainTexts */
		$plainTexts = $this->parser->getPlainTextParts();
		$this->assertCount(1, $plainTexts);
		$this->assertInstanceOf("Merr\\Part\\TextPart", $plainTexts[0]);
		$this->assertEquals("text/plain", $plainTexts[0]->getContentType());
	}

	/**
	 * @test
	 */
	public function getHtmlTextParts()
	{
		/** @var TextPart[] $htmlTexts */
		$htmlTexts = $this->parser->getHtmlTextParts();
		$this->assertCount(1, $htmlTexts);
		$this->assertInstanceOf("Merr\\Part\\TextPart", $htmlTexts[0]);
		$this->assertEquals("text/html", $htmlTexts[0]->getContentType());
	}

	/**
	 * @test
	 */
	public function getAttachmentParts()
	{
		$attachments = $this->parser->getAttachmentParts();
		$this->assertCount(2, $attachments);
		$this->assertInstanceOf("Merr\\Part\\AttachmentPart", $attachments[0]);
		$this->assertEquals("google.png", $attachments[0]->getFilename());
		$this->assertInstanceOf("Merr\\Part\\AttachmentPart", $attachments[1]);
		$this->assertEquals("blogger.png", $attachments[1]->getFilename());
	}

	/**
	 * @test
	 */
	public function getInlineImageParts()
	{
		$inlineImages = $this->parser->getInlineImageParts();
		$this->assertCount(2, $inlineImages);
		$this->assertInstanceOf("Merr\\Part\\InlineImagePart", $inlineImages[0]);
		$this->assertEquals("twitter.png", $inlineImages[0]->getFilename());
		$this->assertInstanceOf("Merr\\Part\\InlineImagePart", $inlineImages[1]);
		$this->assertEquals("facebook.png", $inlineImages[1]->getFilename());
	}

	/**
	 * @test
	 */
	public function hasPart_plainText()
	{
		$mail = getTestMail("01.plain_text_ascii.eml");
		$parser = new Parser($mail);
		$this->assertEquals(true, $parser->hasPlainTextPart());
		$this->assertEquals(false, $parser->hasHtmlTextPart());
		$this->assertEquals(false, $parser->hasAttachmentPart());
		$this->assertEquals(false, $parser->hasInlineImagePart());
	}

	/**
	 * @test
	 */
	public function hasPart_plainText_htmlText()
	{
		$mail = getTestMail("02.html_text_ascii.eml");
		$parser = new Parser($mail);
		$this->assertEquals(true, $parser->hasPlainTextPart());
		$this->assertEquals(true, $parser->hasHtmlTextPart());
		$this->assertEquals(false, $parser->hasAttachmentPart());
		$this->assertEquals(false, $parser->hasInlineImagePart());
	}

	/**
	 * @test
	 */
	public function hasPart_plainText_htmlText_inlineImage_attachment()
	{
		$mail = getTestMail("03.htmltext_inlineimage_attachment.eml");
		$parser = new Parser($mail);
		$this->assertEquals(true, $parser->hasPlainTextPart());
		$this->assertEquals(true, $parser->hasHtmlTextPart());
		$this->assertEquals(true, $parser->hasAttachmentPart());
		$this->assertEquals(true, $parser->hasInlineImagePart());
	}
}
 