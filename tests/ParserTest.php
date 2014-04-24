<?php

use Merr\Parser;

class ParserTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function インスタンス()
    {
        $this->assertInstanceOf("Merr\Parser", new Parser());
    }
}
 