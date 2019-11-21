<?php
// 辅助函数

/**
 * 根据路径获取配置;使用方法如： config('file.param_tag.other_param_tag');
 *
 * @param $path
 * @param string $default
 *
 * @return mixed|string
 */
function config($path = '', $default = '')
{
	// config empty
	if (empty($path)) {
		exit("config file: {$path} is empty!");
	}
	// 配置注册树
	static $config_tree = [];
	// '.' 切割配置，默认第一个数据为配置文件名
	$path_array = explode('.', $path);
	$config_file_name = $path_array[0];
	array_shift($path_array);
	// 查找配置注册树
	if (!isset($config_tree[$config_file_name])) {
		$config_base_path = APP_PATH . '/config';
		$config_path = "{$config_base_path}/{$config_file_name}.php";
		if (!file_exists($config_path)) {
			exit("config file: {$path} is not exists!");
		}
		$config_tree[$config_file_name] = require_once $config_path;
	}
	// 配置数据
	$config_data = $config_tree[$config_file_name];
	if (count($path_array) == 0) {
		return $config_data;
	}
	// 遍历层级
	foreach ($path_array as $temp) {
		// 配置不存在
		if (!isset($config_data[$temp])) {
			return $default;
		}
		$config_data = $config_data[$temp];
	}
	return $config_data;
}

/**
 * array only
 * @param array $data
 * @param array $params
 * @param mixed $default
 *
 * @return array
 */
function array_only(array $data, array $params, $default = null)
{
	$only_array = [];
	foreach ($params as $param) {
		$only_array[$param] = isset($data[$param]) ? $data[$param] : $default;
	}
	return $only_array;
}

/**
 * @param array $data
 * @param array $params
 */
function array_except(array &$data, array $params)
{
	foreach ($params as $field) {
		if (isset($data[$field])) {
			unset($data[$field]);
		}
	}
}

function str_random($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	if (!is_numeric( $length ) || $length < 0) {
		return false;
	}
	$string = '';
	for ($i = $length; $i > 0; $i --) {
		$string .= $char[ mt_rand(0, strlen($char) - 1)];
	}
	return $string;
}
