<?php
use Zend\Mail\Storage\Part;

/**
 * Zend\Mail\Storage\Part の挙動確認用
 */
class ZendMailStoragePartTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Part part
	 */
	private $parts;

	public function setUp()
	{
		$raw = getTestMail("02.html_text_ascii.eml");
		$this->parts = new Part(["raw" => $raw]);
	}

	/**
	 * @test
	 */
	public function インスタンス()
	{
		$this->assertInstanceOf("Zend\\Mail\\Storage\\Part", $this->parts);

		foreach ($this->parts->getHeaders() as $header) {
			$this->assertInstanceOf("Zend\\Mail\\Header\\HeaderInterface", $header);
		}

		for ($i = 1; $i <= $this->parts->countParts(); $i++) {
			$part = $this->parts->getPart($i);
			$this->assertInstanceOf("Zend\\Mail\\Storage\\Part", $part);
		}
	}
}