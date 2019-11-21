<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use \Yaf\Dispatcher;

/**
 * Log
 * Class Log
 */
class Log
{
	/**
	 * default log level
	 */
	const LOG_ERROR = 'error';

	/**
	 * @var array
	 */
	private static $loggers = [];

	/**
	 * get Logger instance
	 *
	 * @param string $type
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private static function getLogger($type = self::LOG_ERROR)
	{
		$module = Dispatcher::getInstance()->getRequest()->getModuleName();
		// instance is empty
		if (empty(self::$loggers[$module . $type])) {
			// new logger
			self::$loggers[$module . $type] = new Logger($type);
			$path = LOG_PATH . '/' . $module;
			if (!is_dir($path)) {
				mkdir($path);
			}
			$path .= '/' . $type;
			if (!is_dir($path)) {
				mkdir($path);
			}
			$file_name = date('Y-m-d');
			$path = "{$path}/{$file_name}.log";
			self::$loggers[$module . $type]->pushHandler(new StreamHandler($path, Logger::DEBUG));
			self::$loggers[$module . $type]->pushHandler(new FirePHPHandler());
		}
		$log = self::$loggers[$module . $type];
		return $log;
	}

	/**
	 * debug
	 *
	 * @param $data
	 */
	public static function debug($data)
	{
		self::_doLog('debug', $data);
	}

	/**
	 * error
	 *
	 * @param $data
	 */
	public static function error($data)
	{
		self::_doLog('error', $data);
	}

	/**
	 * sql
	 *
	 * @param $data
	 */
	public static function sql($data)
	{
		self::_doLog('sql', $data);
	}

	/**
	 * sql error
	 *
	 * @param $data
	 */
	public static function sqlError($data)
	{
		self::_doLog('sqlError', $data);
	}

	/**
	 * bench
	 *
	 * @param $data
	 */
	public static function benchmark($data)
	{
		self::_doLog('benchmark', $data);
	}

	/**
	 * do log
	 *
	 * @param $type
	 * @param $data
	 */
	private static function _doLog($type, $data)
	{
		if (is_array($data) || is_object($data)) {
			$data = json_encode($data, 320);
		}
		self::getLogger($type)->info($data);
	}
}
