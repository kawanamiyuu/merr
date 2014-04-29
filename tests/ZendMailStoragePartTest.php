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
		$raw = getTestMail("03.htmltext_inlineimage_attachement.eml");
		$this->parts = new Part(["raw" => $raw]);
	}

	/**
	 * @test
	 */
	public function parse()
	{
		$this->assertInstanceOf("Zend\\Mail\\Storage\\Part", $this->parts);

		foreach ($this->parts->getHeaders() as $header) {
			$this->assertInstanceOf("Zend\\Mail\\Header\\HeaderInterface", $header);
//			var_dump($header);
		}

		for ($i = 1; $i <= $this->parts->countParts(); $i++) {
			$part = $this->parts->getPart($i);
			$this->assertInstanceOf("Zend\\Mail\\Storage\\Part", $part);
//			var_dump($part->getHeaders());
		}

		$this->assertTrue($this->parts->isMultipart());
		$this->assertEquals(3, $this->parts->countParts());

		{
			$related = $this->parts->getPart(1);
			$this->assertEquals("multipart/related", $related->getHeader("content-type")->getType());

			$this->assertTrue($related->isMultipart());
			$this->assertEquals(3, $related->countParts());

			{
				$alternative = $related->getPart(1);
				$this->assertEquals("multipart/alternative", $alternative->getHeader("content-type")->getType());

				$this->assertTrue($alternative->isMultipart());
				$this->assertEquals(2, $alternative->countParts());

				{
					$plainText = $alternative->getPart(1);
					$this->assertEquals("text/plain", $plainText->getHeader("content-type")->getType());
					$this->assertEquals("ISO-2022-JP", $plainText->getHeader("content-type")->getParameter("charset"));
					$this->assertEquals("7bit", $plainText->getHeader("content-transfer-encoding")->getFieldValue());
				}
				{
					$htmlText = $alternative->getPart(2);
					$this->assertEquals("text/html", $htmlText->getHeader("content-type")->getType());
					$this->assertEquals("ISO-2022-JP", $htmlText->getHeader("content-type")->getParameter("charset"));
					$this->assertEquals("base64", $htmlText->getHeader("content-transfer-encoding")->getFieldValue());
				}
			}
			{
				$inline1 = $related->getPart(2);
				$this->assertEquals('inline; filename="twitter.png"', $inline1->getHeader("content-disposition")->getFieldValue());
				$this->assertEquals("image/png", $inline1->getHeader("content-type")->getType());
				$this->assertEquals("twitter.png", $inline1->getHeader("content-type")->getParameter("name"));
				$this->assertEquals("base64", $inline1->getHeader("content-transfer-encoding")->getFieldValue());
			}
			{
				$inline2 = $related->getPart(3);
				$this->assertEquals('inline; filename="facebook.png"', $inline2->getHeader("content-disposition")->getFieldValue());
				$this->assertEquals("image/png", $inline2->getHeader("content-type")->getType());
				$this->assertEquals("facebook.png", $inline2->getHeader("content-type")->getParameter("name"));
				$this->assertEquals("base64", $inline2->getHeader("content-transfer-encoding")->getFieldValue());
			}
		}
		{
			$attachment1 = $this->parts->getPart(2);
			$this->assertEquals('attachment; filename="google.png"', $attachment1->getHeader("content-disposition")->getFieldValue());
			$this->assertEquals("image/png", $attachment1->getHeader("content-type")->getType());
			$this->assertEquals("google.png", $attachment1->getHeader("content-type")->getParameter("name"));
			$this->assertEquals("base64", $attachment1->getHeader("content-transfer-encoding")->getFieldValue());
		}
		{
			$attachment2 = $this->parts->getPart(3);
			$this->assertEquals('attachment; filename="blogger.png"', $attachment2->getHeader("content-disposition")->getFieldValue());
			$this->assertEquals("image/png", $attachment2->getHeader("content-type")->getType());
			$this->assertEquals("blogger.png", $attachment2->getHeader("content-type")->getParameter("name"));
			$this->assertEquals("base64", $attachment2->getHeader("content-transfer-encoding")->getFieldValue());
		}
	}
}