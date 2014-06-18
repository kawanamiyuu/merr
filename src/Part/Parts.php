<?php

namespace Merr\Part;


class Parts implements \ArrayAccess, \Iterator, \Countable
{
	/**
	 * @var Part[]
	 */
	private $parts;

	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * @param Part[] &$parts parts
	 */
	public function __construct(array &$parts)
	{
		$this->parts =& $parts;
	}

	/**
	 * Return the current element
	 *
	 * @return Part
	 */
	public function current()
	{
		return $this->offsetGet($this->position);
	}

	/**
	 * Move forward to next element
	 */
	public function next()
	{
		$this->position++;
	}

	/**
	 * Return the key of the current element
	 *
	 * @return int the key of the current element
	 */
	public function key()
	{
		return $this->position;
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return boolean true if current position is valid
	 */
	public function valid()
	{
		return $this->offsetExists($this->position);
	}

	/**
	 * Rewind the Iterator to the first element
	 */
	public function rewind()
	{
		$this->position = 0;
		$this->parts = array_values($this->parts);
	}

	/**
	 * Count elements of an object
	 *
	 * @return int count
	 */
	public function count()
	{
		return count($this->parts);
	}

	/**
	 * Remove the current element
	 */
	public function remove()
	{
		$this->offsetUnset($this->position);
	}

	/**
	 * check if an offset exists
	 *
	 * @param int $offset offset
	 * @return boolean true if an offset exists
	 */
	public function offsetExists($offset)
	{
		return isset($this->parts[$offset]);
	}

	/**
	 * Offset to retrieve
	 *
	 * @param mixed $offset offset
	 * @return Part
	 */
	public function offsetGet($offset)
	{
		return $this->parts[$offset];
	}

	/**
	 * Offset to set
	 *
	 * @param mixed $offset the offset to assign the value to
	 * @param mixed $value  the value to set
	 */
	public function offsetSet($offset, $value)
	{
		$this->parts[$offset] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @param mixed $offset the offset to unset
	 */
	public function offsetUnset($offset)
	{
		unset($this->parts[$offset]);
	}
}