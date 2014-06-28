<?php

namespace Merr\Header;


class ContentDisposition
{
	/**
	 * @var string
	 */
	private $disposition;

	/**
	 * @var array
	 */
	private $parameters = [];

	/**
	 * @param string $disposition content-disposition value
	 */
	public function setDisposition($disposition)
	{
		$this->disposition = $disposition;
	}

	/**
	 * @return string content-disposition value
	 */
	public function getDisposition()
	{
		return $this->disposition;
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