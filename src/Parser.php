<?php

namespace Merr;

use Merr\Exception\InvalidArgumentException;
use Merr\Part\AttachmentPart;
use Merr\Part\GenericPart;
use Merr\Part\GenericPartIterator;
use Merr\Part\InlineImagePart;
use Merr\Part\PartInterface;
use Merr\Part\TextPart;
use Merr\Util\ZendMailUtil;
use Zend\Mail\Storage\Part as ZfPart;

class Parser
{
	/**
	 * @var GenericPartIterator
	 */
	private $parts;

	/**
	 * Constructor
	 *
	 * @param string $rawMessage raw message
	 */
	public function __construct($rawMessage)
	{
		// TODO リソース型も許容する？
		if (!is_string($rawMessage)) {
			throw new InvalidArgumentException("argument must be string.");
		}

		$zfPart = new ZfPart(["raw" => $rawMessage]);
		$parts = ZendMailUtil::convertGenericPartRecursively($zfPart);

		$this->parts = new GenericPartIterator($parts);
	}

	/**
	 * @param string $fieldName header field name
	 * @return mixed
	 */
	public function getHeader($fieldName)
	{
		// TODO 実装
		return null;
	}

	public function getFrom()
	{
		// TODO 実装
		return null;
	}

	public function getTo()
	{
		// TODO 実装
		return null;
	}

	public function getCc()
	{
		// TODO 実装
		return null;
	}

	public function getBcc()
	{
		// TODO 実装
		return null;
	}

	public function getSubject()
	{
		// TODO 実装
		return null;
	}


	/**
	 * @param callable      $callback
	 * @param PartInterface $retPart
	 * @return GenericPart[]|PartInterface[]
	 */
	public function getParts(callable $callback = null, PartInterface $retPart = null)
	{
		$results = [];

		while ($this->parts->valid()) {
			if ($callback == null) {
				$results[] = $this->parts->current();
				$this->parts->remove();
			} else {
				if ($callback($this->parts->current())) {
					if ($retPart !== null) {
						$reflClass = new \ReflectionClass($retPart);
						/** @var PartInterface $retPartInst */
						$retPartInst = $reflClass->newInstance();
						$retPartInst->setGenericPart($this->parts->current());
						$results[] = $retPartInst;
					} else {
						$results[] = $this->parts->current();
					}
					$this->parts->remove();
				}
			}
			$this->parts->next();
		}

		$this->parts->rewind();

		return $results;
	}

	/**
	 * @return TextPart[] (inline) plain text parts
	 */
	public function getPlainTextParts()
	{
		return $this->getParts(function(GenericPart $part) {
			return $part->isPlainTextPart();
		}, new TextPart());
	}

	/**
	 * @return TextPart[] (inline) html text parts
	 */
	public function getHtmlTextParts()
	{
		return $this->getParts(function(GenericPart $part) {
			return $part->isHtmlTextPart();
		}, new TextPart());
	}

	/**
	 * @return AttachmentPart[] attachment parts
	 */
	public function getAttachmentParts()
	{
		return $this->getParts(function(GenericPart $part) {
			return $part->isAttachmentPart();
		}, new AttachmentPart());

	}

	/**
	 * @return InlineImagePart[] inline image parts
	 */
	public function getInlineImageParts()
	{
		return $this->getParts(function(GenericPart $part) {
			return $part->isInlineImagePart();
		}, new InlineImagePart());
	}

	/**
	 * @return bool true, if this message has plain text part
	 */
	public function hasPlainTextPart()
	{
		foreach ($this->parts as $part) {
			/** @var GenericPart $part */
			if ($part->isPlainTextPart()) {
				$this->parts->rewind();
				return true;
			}
		}

		$this->parts->rewind();
		return false;
	}

	/**
	 * @return bool true, if this message has html text part
	 */
	public function hasHtmlTextPart()
	{
		foreach ($this->parts as $part) {
			/** @var GenericPart $part */
			if ($part->isHtmlTextPart()) {
				$this->parts->rewind();
				return true;
			}
		}

		$this->parts->rewind();
		return false;
	}

	/**
	 * @return bool true, if this message has attachment part
	 */
	public function hasAttachmentPart()
	{
		foreach ($this->parts as $part) {
			/** @var GenericPart $part */
			if ($part->isAttachmentPart()) {
				$this->parts->rewind();
				return true;
			}
		}

		$this->parts->rewind();
		return false;
	}

	/**
	 * @return bool true, if this message has inline image part
	 */
	public function hasInlineImagePart()
	{
		foreach ($this->parts as $part) {
			/** @var GenericPart $part */
			if ($part->isInlineImagePart()) {
				$this->parts->rewind();
				return true;
			}
		}

		$this->parts->rewind();
		return false;
	}
}