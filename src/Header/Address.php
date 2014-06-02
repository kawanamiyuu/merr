<?php

namespace Merr\Header;


class Address
{
	/**
	 * @var string
	 */
	private $address;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * Constructor
	 * 
	 * @param string $address address
	 * @param string $name name
	 */
	public function __construct($address, $name = null)
	{
		$this->address = $address;
		$this->name = $name;
	}

	/**
	 * @return string address
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @return string name
	 */
	public function getName()
	{
		return $this->name;
	}
} 