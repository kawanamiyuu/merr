<?php

namespace Merr\Part;


class InlineImagePart extends AbstractPart
{
	/**
	 * @return string inline image content
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

	/**
	 * @return string file name
	 */
	public function getFilename()
	{
		return $this->getGenericPart()->getContentDisposition()->getParameter("filename");
	}

	/**
	 * @return string content-id
	 */
	public function getContentId()
	{
		return $this->getGenericPart()->getContentId()->getId();
	}
} 