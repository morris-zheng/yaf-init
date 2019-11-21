<?php

namespace Helpers;

/**
 * 变量缓存
 * Class VarCache
 */
class VarCache
{
	/**
	 * 变量数据
	 * @var array
	 */
    private static $_var_data = [];

	/**
	 * set（single）
	 * @param $key
	 * @param $value
	 * @param string $field
	 */
    public static function set($key, $value, $field = '')
    {
        if (empty($key)) {
            exit('key is empty');
        }
        if ($field != '') {
            if (!isset(self::$_var_data[$key])) {
                self::$_var_data[$key] = [];
            }
            self::$_var_data[$key][$field] = $value;
        } else {
            self::$_var_data[$key] = $value;
        }
    }

	/**
	 * get
	 * @param $key
	 * @param string $default
	 *
	 * @return mixed|string
	 */
    public static function get($key, $default = '')
    {
        $key_array = explode('.', $key);
        if (count($key_array) > 2) {
            exit("The key: {$key} is wrong!");
        }
        // set key
        $key = $key_array[0];
        $field = '';
        if (isset($key_array[1])) {
            $field = $key_array[1];
        }
        if (!isset(self::$_var_data[$key])) {
            return $default;
        }
        if ($field != '') {
            return isset(self::$_var_data[$key][$field]) ? self::$_var_data[$key][$field] : $default;
        } else {
            return self::$_var_data[$key];
        }
    }
}
