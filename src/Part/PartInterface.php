<?php

namespace Merr\Part;


interface PartInterface
{
	/**
	 * @param GenericPart $part
	 */
	public function setGenericPart(GenericPart $part);

	/**
	 * @return GenericPart part
	 */
	public function getGenericPart();
}