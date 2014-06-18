<?php

namespace Merr\Part;


class AbstractPart implements PartInterface
{
	/**
	 * @var Part
	 */
	private $part;

	/**
	 * @param Part $part
	 */
	public function setPart(Part $part)
	{
		$this->part = $part;
	}

	/**
	 * @return Part part
	 */
	public function getPart()
	{
		return $this->part;
	}
}