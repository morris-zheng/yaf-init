<?php

use Helpers\Code;

/**
 * Class Response
 */
class Response
{
	/**
	 * @param $data
	 * @param $msg
	 * @param $status
	 * @param $code
	 *
	 * @return mixed
	 */
	public static function doResponse($data, $msg, $status, $code)
	{
		$data = !empty($data) ? $data : new \stdClass();
		// content-type
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode([
			'data'   => $data,
			'msg'    => $msg,
			'status' => $status,
			'code'   => $code
		], JSON_UNESCAPED_UNICODE);
		return true;
	}

	/**
	 * @param array $data
	 * @param string $msg
	 *
	 * @return mixed
	 */
	public static function success($data = [], $msg = 'success')
	{
		return self::doResponse($data, $msg, Code::$STATUS_SUCCESS, Code::$CODE_SUCCESS);
	}

	/**
	 * @param string $msg
	 * @param int $code
	 * @param array $data
	 *
	 * @return mixed
	 */
	public static function fail($msg = '', $code = 0, $data = [])
	{
		if (empty($code)) {
			$code = Code::$CODE_FAIL;
		}
		if (empty($msg)) {
			$msg = '服务器异常：50000';
		}
		return self::doResponse($data, $msg, Code::$STATUS_FAIL, $code);
	}
}
