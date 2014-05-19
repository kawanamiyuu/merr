<?php
use Zend\Mail\Storage\Part;
use Zend\Mime\Decode;

/**
 * Zend\Mail\Storage\Part の挙動確認用
 */
class ZendTest extends PHPUnit_Framework_TestCase
{
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

		$this->assertEquals("1.0", $this->parts->getHeader("mime-version")->getFieldValue());
		$this->assertEquals("Mon, 28 Apr 2014 20:54:57 +0900", $this->parts->getHeader("date")->getFieldValue());
		$this->assertEquals("<CADHfj_GHVHvgPXYqqBAJEdG+zTsCM299CfnTr-N3sXLsRTQe4g@example.com>", $this->parts->getHeader("message-id")->getFieldValue());
		$this->assertEquals("テストメールの件名", $this->parts->getHeader("subject")->getFieldValue());
		$this->assertEquals("送信者(From)の名前 <from-addr@example.com>", $this->parts->getHeader("from")->getFieldValue());
		$this->assertEquals("宛先(To)の名前 <to-addr@example.com>", $this->parts->getHeader("to")->getFieldValue());

		$this->assertEquals("multipart/mixed", $this->parts->getHeader("content-type")->getType());

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
					$this->assertNotEmpty($plainText->getContent());
				}
				{
					$htmlText = $alternative->getPart(2);
					$this->assertEquals("text/html", $htmlText->getHeader("content-type")->getType());
					$this->assertEquals("ISO-2022-JP", $htmlText->getHeader("content-type")->getParameter("charset"));
					$this->assertEquals("base64", $htmlText->getHeader("content-transfer-encoding")->getFieldValue());
					$this->assertNotEmpty($htmlText->getContent());
				}
			}
			{
				$inline1 = $related->getPart(2);
				$this->assertEquals('inline; filename="twitter.png"', $inline1->getHeader("content-disposition")->getFieldValue());
				$this->assertEquals("image/png", $inline1->getHeader("content-type")->getType());
				$this->assertEquals("twitter.png", $inline1->getHeader("content-type")->getParameter("name"));
				$this->assertEquals("base64", $inline1->getHeader("content-transfer-encoding")->getFieldValue());
				$this->assertEquals("<ii_145a82f8d1abc6fd>", $inline1->getHeader("content-id")->getFieldValue());
				$this->assertNotEmpty($inline1->getContent());
			}
			{
				$inline2 = $related->getPart(3);
				$this->assertEquals('inline; filename="facebook.png"', $inline2->getHeader("content-disposition")->getFieldValue());
				$this->assertEquals("image/png", $inline2->getHeader("content-type")->getType());
				$this->assertEquals("facebook.png", $inline2->getHeader("content-type")->getParameter("name"));
				$this->assertEquals("base64", $inline2->getHeader("content-transfer-encoding")->getFieldValue());
				$this->assertEquals("<ii_145a82ec4f0639fc>", $inline2->getHeader("content-id")->getFieldValue());
				$this->assertNotEmpty($inline2->getContent());
			}
		}
		{
			$attachment1 = $this->parts->getPart(2);
			$this->assertEquals('attachment; filename="google.png"', $attachment1->getHeader("content-disposition")->getFieldValue());
			$this->assertEquals("image/png", $attachment1->getHeader("content-type")->getType());
			$this->assertEquals("google.png", $attachment1->getHeader("content-type")->getParameter("name"));
			$this->assertEquals("base64", $attachment1->getHeader("content-transfer-encoding")->getFieldValue());
			$this->assertNotEmpty($attachment1->getContent());
		}
		{
			$attachment2 = $this->parts->getPart(3);
			$this->assertEquals('attachment; filename="blogger.png"', $attachment2->getHeader("content-disposition")->getFieldValue());
			$this->assertEquals("image/png", $attachment2->getHeader("content-type")->getType());
			$this->assertEquals("blogger.png", $attachment2->getHeader("content-type")->getParameter("name"));
			$this->assertEquals("base64", $attachment2->getHeader("content-transfer-encoding")->getFieldValue());
			$this->assertNotEmpty($attachment2->getContent());
		}
	}

	/**
	 * @test
	 */
	public function ZendMimeDecode_splitHeaderField()
	{
		$ret = Decode::splitHeaderField('value; pName1="pValue1"; pName2="pValue2"');
		$this->assertEquals("value", $ret[0]);
		$this->assertEquals("pValue1", $ret["pname1"]);
		$this->assertEquals("pValue2", $ret["pname2"]);
	}
}