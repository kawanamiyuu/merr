<?php

namespace Merr\Part;


class TextPart extends AbstractPart
{
	/**
	 * @var string charset
	 */
	private $charset;

	/**
	 * @param string $charset charset
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;
	}

	/**
	 * @return string charset
	 */
	public function getCharset()
	{
		return $this->charset;
	}
}