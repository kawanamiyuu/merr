<?php

namespace Merr\Part;


class AttachmentPart extends AbstractPart
{
	/**
	 * @return string attachment content
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
} 