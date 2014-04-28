<?php

use Merr\Parser;

class ParserTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function インスタンス()
	{
		$mail = getTestMail("01.plain_text_ascii.eml");
		$parser = new Parser($mail);
		$this->assertInstanceOf("Merr\\Parser", $parser);
	}
}
 