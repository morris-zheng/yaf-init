<?php

use \Yaf\Controller_Abstract;

/**
 * Class BaseController
 * base controller
 */
class BaseController extends Controller_Abstract
{
	public function init()
	{

	}

	/**
	 * disable view
	 */
	protected function disableView()
	{
		Yaf\Dispatcher::getInstance()->disableView();
	}

	/**
	 * get
	 * @param $param
	 * @param string $default
	 *
	 * @return mixed
	 */
	public function get($param, $default = '')
	{
		return $this->getRequest()->getQuery($param, $default);
	}

	/**
	 * post
	 * @param $param
	 * @param string $default
	 *
	 * @return mixed
	 */
	public function post($param, $default = '')
	{
		return $this->getRequest()->getPost($param, $default);
	}

	/**
	 * files
	 * @return mixed
	 */
	public function files()
	{
		return $this->getRequest()->getFiles();
	}

	/**
	 * body
	 * @return false|string
	 */
	public function body()
	{
		return file_get_contents("php://input");
	}

	/**
	 * request
	 *
	 * @param string $param field
	 * @param string $default default
	 * @return mixed
	 */
	public function request(string $param = '', $default = '')
	{
		return empty($param) ? $this->getRequest()->getRequest() : $this->getRequest()->getRequest($param, $default);
	}

	/**
	 * array only from request
	 * @param array $params
	 * @param string $default
	 *
	 * @return array
	 */
	public function only(array $params, $default = '')
	{
		$data = [];
		foreach ($params as $param) {
			$data[$param] = isset($this->request()[$param]) ? $this->request()[$param] : $default;
		}
		return $data;
	}
}
