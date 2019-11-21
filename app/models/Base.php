<?php

use Medoo\Medoo;

/**
 * Base Model
 * Class BaseModel
 */
class BaseModel
{
	/**
	 * table
	 * @var
	 */
	protected $table;
	/**
	 * primary key
	 * @var string
	 */
	protected $primary_key = 'id';
	/**
	 * 创建时间字段
	 * @var string
	 */
	protected $created_at = 'created_at';
	/**
	 * 更新时间字段
	 * @var string
	 */
	protected $updated_at = 'updated_at';
	/**
	 * 删除时间字段
	 * @var string
	 */
	protected $deleted_at = 'deleted_at';
	/**
	 * 是否软删除
	 * @var bool
	 */
	protected $is_soft_delete = true;
	/**
	 * 隐藏字段
	 * @var array
	 */
	protected $hidden = [];
	/**
	 * 错误信息
	 * @var string
	 */
	private $error = '';
	/**
	 * database accessor
	 * @var Medoo
	 */
	private $_db_accessor;
	/**
	 * database instance
	 * @var Medoo
	 */
	private static $_db_instance;
	/**
	 * model tree
	 * @var array
	 */
	static $_model_tree = [];

	/**
	 * single
	 *
	 * @param $class_name
	 *
	 * @return mixed
	 */
	public static function run($class_name)
	{
		if (empty(self::$_model_tree[$class_name])) {
			self::$_model_tree[$class_name] = new $class_name();
		}
		return self::$_model_tree[$class_name];
	}

	/**
	 * constructor
	 *
	 * BaseModel constructor.
	 *
	 * @param $config
	 */
	public function __construct()
	{
		if (empty(self::$_db_instance)) {
			$config = config('database');
			$config_init = [
				'database_type' => 'mysql',
				'database_name' => $config['database'],
				'server' => $config['host'],
				'username' => $config['username'],
				'password' => $config['password']
			];
			self::$_db_instance = new Medoo($config_init);
		}
		$this->_db_accessor = self::$_db_instance;
	}

	/**
	 * find
	 *
	 * @param array $columns
	 * @param array $where
	 *
	 * @return array|mixed
	 */
	public function find($columns = ['*'], $where = [])
	{
		$this->_dealDeletedAt($where);
		$data = $this->_db_accessor->get($this->table, $columns, $where);
		$this->_log();
		if (empty($data)) {
			return [];
		}
		return $data;
	}

	/**
	 * get
	 *
	 * @param array $columns
	 * @param array $where
	 *
	 * @return array|mixed
	 */
	public function get($columns = ['*'], $where)
	{

		$this->_dealDeletedAt($where);
		$data = $this->_db_accessor->select($this->table, $columns, $where);
		$this->_log();
		if (empty($data)) {
			return [];
		}
		return $data;
	}

	/**
	 * @param $datas
	 *
	 * @return bool|int|mixed|string
	 */
	public function save($data)
	{
		$return = 0;
		$time = time();
		$data[$this->updated_at] = $time;
		// 根据主键更新
		if (!empty($data[$this->primary_key])) {
			$where = [$this->primary_key => $data[$this->primary_key]];
			$res = $this->_db_accessor->update($this->table, $data, $where);
			if (!empty($res->rowCount())) {
				$return = $data[$this->primary_key];
			}
		} else {
			$data[$this->created_at] = $time;
			$res = $this->_db_accessor->insert($this->table, $data);
			if (!empty($res->rowCount())) {
				$return = $this->_db_accessor->id();
			}
		}
		$this->_log();
		return (int)$return;
	}

	/**
	 * 更新
	 *
	 * @param array $where
	 * @param array $data
	 *
	 * @return bool|PDOStatement
	 */
	public function update($where = [], $data = [])
	{
		$this->_dealDeletedAt($where);
		$where[$this->updated_at] = time();
		$res = $this->_db_accessor->update($this->table, $data, $where);
		$this->_log();
		if (empty($res->rowCount())) {
			return false;
		}
		return true;
	}

	/**
	 * 删除
	 * @param $where
	 */
	public function delete($where)
	{
		// 软删除
		if ($this->is_soft_delete == true) {
			$this->_db_accessor->update($this->table, [ $this->deleted_at => time()], $where);
		} else {
			$this->_db_accessor->delete($this->table, $where);
		}
		$this->_log();
	}

	/**
	 * query single with sql
	 *
	 * @param $sql
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function query($sql, $params = [])
	{
		return $this->_db_accessor->query($sql, $params)->fetch();
	}

	/**
	 * query all with sql
	 *
	 * @param $sql
	 * @param array $params
	 *
	 * @return array
	 */
	public function queryAll($sql, $params = [])
	{
		return $this->_db_accessor->query($sql, $params)->fetchAll();
	}

	/**
	 * @return Medoo
	 */
	public function getDbAccessor()
	{
		return $this->_db_accessor;
	}
	/**
	 * 处理软删字段
	 * @param $where
	 */
	private function _dealDeletedAt(&$where)
	{
		if (!empty($this->deleted_at)) {
			if (!isset($where['with_trash']) || $where['with_trash'] == false) {
				$where[$this->deleted_at . '[=]'] = 0;
			}
		}
	}

	/**
	 * log
	 *
	 * @return bool
	 */
	private function _log()
	{
		// sql log switch
		if (config('database.sql_log') !=  true) {
			return false;
		}
		$sql = $this->_db_accessor->log();
		$sql_error = $this->_db_accessor->error();
		// error
		if (!empty($sql_error[2])) {
			$this->error = $sql_error;
			Log::sqlError([
				$sql_error,
				$sql
			]);
		} else {
			// normal
			Log::sql($sql);
		}
		return true;
	}
}
