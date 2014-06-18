<?php

namespace Merr\Part;


class InlineImagePart extends AbstractPart
{
	/**
	 * @return string inline image content
	 */
	public function getContent()
	{
		return $this->getPart()->getContent();
	}

	/**
	 * @return string content-type
	 */
	public function getContentType()
	{
		return $this->getPart()->getContentType()->getType();
	}

	/**
	 * @return string file name
	 */
	public function getFilename()
	{
		return $this->getPart()->getContentDisposition()->getParameter("filename");
	}

	/**
	 * @return string content-id
	 */
	public function getContentId()
	{
		return $this->getPart()->getContentId()->getId();
	}
} 