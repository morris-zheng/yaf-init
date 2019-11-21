<?php

namespace Helpers;

/**
 * Class Rsa
 * @package Helpers
 */
class Rsa
{
	static $_iv = '12345678910jqk12';
	static $_method = 'AES128';
	static $_key = 'Rsa&Key';

	/**
	 * @param string $iv
	 */
	public static function setIv($iv)
	{
		self::$_iv = $iv;
	}

	/**
	 * @param string $method
	 */
	public static function setMethod($method)
	{
		self::$_method = $method;
	}

	/**
	 * @param string $key
	 */
	public static function setKey($key)
	{
		self::$_key = $key;
	}

	/**
	 * encrypt
	 *
	 * @param $string
	 * @param $key
	 * @param string $iv
	 * @param string $method
	 *
	 * @return string
	 */
	public static function encrypt($string,  $key = '')
	{
		if (empty($key)) {
			$key = self::$_key;
		}
		return openssl_encrypt($string, self::$_method, $key, 0, self::$_iv);
	}

	/**
	 * decrypt
	 *
	 * @param $string
	 * @param $key
	 * @param string $iv
	 * @param string $method
	 *
	 * @return string
	 */
	public static function decrypt($string, $key = '')
	{
		if (empty($key)) {
			$key = self::$_key;
		}
		return openssl_decrypt($string, self::$_method, $key, 0, self::$_iv);
	}
}
