<?php

use Merr\Parser;

class ParserTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function インスタンス()
	{
		$this->assertInstanceOf("Merr\\Parser", new Parser());
	}

	/**
	 * @test
	 */
	public function zend_mail_messageでパース()
	{
		$mail = getTestMail("01.plain_text_ascii.eml");
		$message = \Zend\Mail\Message::fromString($mail);

		$this->assertInstanceOf("\\Zend\\Mail\\Headers", $message->getHeaders());
		$this->assertTrue(is_string($message->getBody()));
	}
}
 