<?php

namespace Helpers;
use \Redis;
use Log;

/**
 * Redis 帮助类
 *
 * Class RedisHelper
 * @package Helpers
 * desc
 */
class RedisHelper
{
	/**
	 * redis instance
	 * @var
	 */
	private static $_redis = [];

	/**
	 * RedisHelper constructor.
	 */
	public function __construct($name, $config)
	{
		if (empty($config['host']) || empty($config['port'])) {
			Log::error('redis config empty');
			return false;
		}
		self::$_redis[$name] = new Redis();
		self::$_redis->pconnect($config['host'], $config['port']);
		// db_index etc.
		$index = !empty($config['index']) ? $config['index'] : 0;
		self::$_redis->select($index);
	}

	/**
	 * single
	 * @return bool|Redis
	 */
	public static function getInstance($config = [], $name = 'default')
	{
		if (!(self::$_redis instanceof Redis)) {
			if (empty($config)) {
				$config = config('redis');
			}
			new RedisHelper($name, $config);
		}
		return self::$_redis[$name];
	}

	/**
	 * db index
	 * @param int $index
	 */
	public function index($index = 0)
	{
		self::$_redis->select($index);
	}
}
