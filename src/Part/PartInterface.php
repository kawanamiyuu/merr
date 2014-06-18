<?php

namespace Merr\Part;


interface PartInterface
{
	/**
	 * @param Part $part
	 */
	public function setPart(Part $part);

	/**
	 * @return Part part
	 */
	public function getPart();
}