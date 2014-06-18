<?php

namespace Merr\Part;


class AttachmentPart extends AbstractPart
{
	/**
	 * @return string attachment content
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
} 