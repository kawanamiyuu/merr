<?php

namespace Merr\Part;


class AbstractPart implements PartInterface
{
	/**
	 * @var GenericPart
	 */
	private $part;

	/**
	 * @param GenericPart $part
	 */
	public function setGenericPart(GenericPart $part)
	{
		$this->part = $part;
	}

	/**
	 * @return GenericPart part
	 */
	public function getGenericPart()
	{
		return $this->part;
	}
}