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
						$retPart->setGenericPart($this->parts->current());
						$results[] = $retPart;
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
	 * @return TextPart[] plain text parts
	 */
	public function getPlainTextParts()
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return TextPart[] html text parts
	 */
	public function getHtmlTextParts()
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return AttachmentPart[] attachment parts
	 */
	public function getAttachmentParts()
	{
		// TODO 実装
		return null;

	}

	/**
	 * @return InlineImagePart[] inline image parts
	 */
	public function getInlineImageParts()
	{
		// TODO 実装
		return null;
	}

	/**
	 * @return bool whether this message has plain text part
	 */
	public function hasPlainTextPart()
	{
		// TODO 実装
		return false;
	}

	/**
	 * @return bool whether this message has rich text part
	 */
	public function hasRichTextPart()
	{
		// TODO 実装
		return false;
	}

	/**
	 * @return bool whether this message has attachment part
	 */
	public function hasAttachmentPart()
	{
		// TODO 実装
		return false;
	}

	/**
	 * @return bool whether this message has inline image part
	 */
	public function hasInlineImagePart()
	{
		// TODO 実装
		return false;
	}
}