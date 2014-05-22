<?php

namespace Merr\Part;


class TextPart extends AbstractPart
{
	/**
	 * @return string text content
	 */
	public function getContent()
	{
		return $this->getGenericPart()->getContent();
	}

	/**
	 * @return string content-type
	 */
	public function getContentType()
	{
		return $this->getGenericPart()->getContentType()->getType();
	}
}