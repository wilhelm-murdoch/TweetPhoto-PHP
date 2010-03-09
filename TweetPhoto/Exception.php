<?php

class TweetPhoto_Exception extends Exception
{
	private $ExceptionIterator;

	static private $singleton = null;


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function __construct()
	{
		$this->ExceptionIterator = new TweetPhoto_Iterator;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function singleton($message = null)
	{
		if(false == self::$singleton instanceof self)
		{
			self::$singleton = new self;
		}

		if(false == is_null($message))
		{
			self::$singleton->addMessage($message);
		}

		return self::$singleton;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function addMessage($message)
	{
		return $this->ExceptionIterator[] = $message;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getIterator()
	{
		return $this->ExceptionIterator;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getLastMessage()
	{
		if(false == count($this->ExceptionIterator))
		{
			return null;
		}

		return $this->ExceptionIterator->pop();
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function __toString()
	{
		return $this->getLastMessage();
	}
}