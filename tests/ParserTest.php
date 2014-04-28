<?php

use Merr\Parser;
use Zend\Mail\Storage\Part;

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

	/**
	 * @test
	 */
	public function zend_mail_partでパース()
	{
		$mail = getTestMail("02.html_text_ascii.eml");
		$part = new Part(["raw" => $mail]);
		foreach ($part->getHeaders() as $header) {
			var_dump($header);
		}
		for ($i = 1; $i <= $part->countParts(); $i++) {
			var_dump($part->getPart($i));
		}
	}
}
 