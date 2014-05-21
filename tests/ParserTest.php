<?php

use Merr\Parser;
use Merr\Part\GenericPart;
use Merr\Part\GenericPartIterator;

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
		$this->assertInstanceOf(GenericPartIterator::class, $parts);
		$this->assertCount(6, $parts);
		$this->assertCount(0, $this->parser->getParts());
	}

	/**
	 * @test
	 */
	public function getParts_callback()
	{
		$attachments = $this->parser->getParts(function(GenericPart $part) {
			return $part->getContentDisposition()->getDisposition() === "attachment";
		});
		$this->assertInstanceOf(GenericPartIterator::class, $attachments);
		$this->assertCount(2, $attachments);

		$plainTexts = $this->parser->getParts(function(GenericPart $part) {
			return $part->getContentType()->getType() === "text/plain";
		});
		$this->assertInstanceOf(GenericPartIterator::class, $plainTexts);
		$this->assertCount(1, $plainTexts);

		$this->assertCount(3, $this->parser->getParts());
		$this->assertCount(0, $this->parser->getParts());
	}
}
 