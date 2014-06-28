<?php

namespace Merr\Header;


class ContentType
{
	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var array
	 */
	private $parameters = [];

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string content-type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $name  parameter name
	 * @param string $value parameter value
	 */
	public function addParameter($name, $value)
	{
		$this->parameters[$name] = $value;
	}

	/**
	 * @param string $name parameter name
	 * @return string parameter value
	 */
	public function getParameter($name)
	{
		return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
	}

	/**
	 * @return array parameter values
	 */
	public function getParameters()
	{
		return $this->parameters;
	}
}