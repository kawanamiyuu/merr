<?php

namespace Merr\Header;


class ContentId
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @param string $id content-id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string content-id
	 */
	public function getId()
	{
		return $this->id;
	}
} 