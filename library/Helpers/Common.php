<?php

namespace Helpers;

/**
 * common tools
 * Class Common
 * @package Helpers
 */
class Common
{
	/**
	 * 获取header参数
	 * @param $header_name
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	public static function getHeaderByName($header_name, $default = '')
	{
		$header_name = strtoupper('http_' . $header_name);
		return isset($_SERVER[$header_name]) ? $_SERVER[$header_name] : $default;
	}
}
