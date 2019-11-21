<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{
	/**
	 * init config
	 */
	public function _initConfig()
	{
		define('STORAGE_PATH', APP_PATH . '/storage');
		define('LOG_PATH', STORAGE_PATH . '/log');
		define('CACHE_PATH', STORAGE_PATH . '/cache');
		ini_set('date.timezone','Asia/Shanghai');
	}

	/**
	 * init router
	 *
	 * @param \Yaf\Dispatcher $dispatcher
	 */
	public function _initRouter(Yaf\Dispatcher $dispatcher)
	{
		// 路由规则可自定义修改
		$uri = $dispatcher->getRequest()->getRequestUri();
		$uri = substr($uri, 1, (strlen($uri) - 1));
		$uri_array = explode('/', $uri);
		$module = !empty($uri_array[0]) ? $uri_array[0] : Yaf\Application::app()->getConfig()['application.dispatcher.defaultModule'];
		$controller = !empty($uri_array[1]) ? $uri_array[1] : 'index';
		$action = !empty($uri_array[2]) ? $uri_array[2] : 'index';
		$dispatcher->getRequest()->setModuleName($module);
		$dispatcher->getRequest()->setControllerName($controller);
		$dispatcher->getRequest()->setActionName($action);
		// cli
		if (php_sapi_name() != 'cli' && $module == 'cli') {
			Response::fail('forbidden');
		}
	}

	/**
	 * custom autoload
	 * @param \Yaf\Dispatcher $dispatcher
	 */
	public function _initAutoload(Yaf\Dispatcher $dispatcher)
	{
		$module = $dispatcher->getRequest()->getModuleName();
		$base_path = APP_PATH . "/app/modules/{$module}";
		// suffix => direction
		$match_array = [
			'Service' => 'services',
			'Repository' => 'repositories'
		];
		spl_autoload_register(function ($name) use ($base_path, $match_array) {
			foreach ($match_array as $suffix => $direction) {
				if (preg_match("/{$suffix}$/", $name) && file_exists("{$base_path}/{$direction}/{$name}.php")) {
					Yaf\Loader::import("{$base_path}/{$direction}/{$name}.php");
				}
			}
		}, true, true);
	}
}
