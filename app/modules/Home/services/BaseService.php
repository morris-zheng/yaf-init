<?php

/**
 * Base Service
 *
 * Class BaseService
 */
class BaseService
{
	/**
	 * data
	 * @var array
	 */
	private $data = [];
	/**
	 * message
	 * @var string
	 */
	private $message = '';
	/**
	 * code
	 * @var int
	 */
	private $code = 0;

	/**
	 * set data
	 * @param array $data
	 *
	 * @return bool
	 */
	protected function setData(array $data)
	{
		$this->data = $data;
		return true;
	}

	/**
	 * set error
	 * @param $message
	 * @param $code
	 *
	 * @return bool
	 */
	protected function setError(string $message, int $code, array $data = [])
	{
		$this->message = $message;
		$this->code    = $code;
		if (!empty($data)) {
			$this->data = $data;
		}
		return false;
	}

	/**
	 * get data
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * get message
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * get code
	 *
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}
}
