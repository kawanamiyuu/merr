<?php

require_once __DIR__ . "/../vendor/autoload.php";

define("TEST_DATA_DIR", __DIR__ . "/data");

/**
 * @param string $fileName テストメールファイル名
 * @return string テストメール
 */
function getTestMail($fileName)
{
	return file_get_contents(TEST_DATA_DIR . "/" . $fileName);
}
