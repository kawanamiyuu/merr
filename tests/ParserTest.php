<?php

use Merr\Parser;
use Merr\Part\AttachmentPart;
use Merr\Part\GenericPart;
use Merr\Part\TextPart;

class ParserTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Parser
	 */
	private $parser;

	public function setUp()
	{
		$mail = getTestMail("03.htmltext_inlineimage_attachement.eml");
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
		$plainTexts = $this->parser->getParts(function(GenericPart $part) {
			return $part->getContentType()->getType() === "text/plain";
		});

		$this->assertCount(1, $plainTexts);
		$this->assertInstanceOf("Merr\\Part\\GenericPart", $plainTexts[0]);
		$this->assertEquals("text/plain", $plainTexts[0]->getContentType()->getType());

		$attachments = $this->parser->getParts(function(GenericPart $part) {
			return $part->getContentDisposition()->getDisposition() === "attachment";
		});

		$this->assertCount(2, $attachments);
		$this->assertInstanceOf("Merr\\Part\\GenericPart", $attachments[0]);
		$this->assertEquals("google.png", $attachments[0]->getContentDisposition()->getParameter("filename"));
		$this->assertInstanceOf("Merr\\Part\\GenericPart", $attachments[1]);
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
		$plainTexts = $this->parser->getParts(function(GenericPart $part) {
			return $part->getContentType()->getType() === "text/plain";
		}, new TextPart);

		$this->assertCount(1, $plainTexts);
		$this->assertInstanceOf("Merr\\Part\\TextPart", $plainTexts[0]);
		$this->assertEquals("text/plain", $plainTexts[0]->getContentType());

		/** @var AttachmentPart[] $attachments */
		$attachments = $this->parser->getParts(function(GenericPart $part) {
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
}
 