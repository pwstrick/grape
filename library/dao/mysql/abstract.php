<?php

class abstractMysqlDao extends Dao {
	protected $table_name = null;//表名
	protected $db = null;//数据库
	protected $primary_key = 'id';//主键
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function getDefault() {
		return $this->init_db('default');
	}
	
	/**
	 * 添加数据
	 * @param array $row
	 * @return mixed 返回主键信息 错误返回0
	 */
	public function insert($row) {
		return $this->db->insert($row, $this->table_name);
	}
	
	/**
	 * 获取一条数据，直接编写sql语句
	 * @param string $sql
	 * @return array|false
	 */
	public function getRowSQL($sql) {
		return $this->db->get_one_sql($sql);
	}
	
	/**
	 * 获取多条数据，直接编写sql语句
	 * @param string $sql
	 * @return array 错误返回false
	 */
	public function getRowsSQL($sql) {
		return $this->db->get_all_sql($sql);
	}
	
	/**
	 * 获取数量
	 * @param array $where
	 * @param string $field
	 * @param boolean $distinct 是否要排除重复
	 * @return int
	 */
	public function getCounts($where = null, $field = '*', $distinct = FALSE) {
		return $this->db->get_count($this->table_name, $where, $field, $distinct);
	}
	
	/**
	 * 根据条件获取一条数据
	 * @param array $where
	 * @param array $order
	 * @param string $field
	 * @return array 错误返回false
	 */
	public function getRow($where = array(), $order = null, $field = '*') {
		return $this->db->get_one_by_field($where, $this->table_name, $order, $field);
	}

	/**
	 * 根据条件获取多条数据
	 * @param array $where
	 * @param array $order
	 * @param int $size
	 * @param int $offset
	 * @param string $field
	 * @return array 错误返回false
	 */
	public function getRows($where = array(), $order = null, $size = null, $offset = 0, $field = '*') {
		return $this->db->get_all($this->table_name, $size, $offset, $where, $order, $field, false);
	}
	
	/**
	 * 根据主键获取一条信息
	 * @param mixed $id 错误返回false
	 */
	public function getRowById($id) {
		return $this->db->get_one($id, $this->table_name, $this->primary_key);
	}
	
	/**
	 * 分页获取数据信息
	 * @param array $where
	 * @param array $order
	 * @param int $page
	 * @param int $size
	 * @param string $field
	 * @return array (0=>数据,1=>数量) 错误返回false
	 */
	public function getRowsLimitByPage($where = array(), $order = null, $page = 1, $size = 10, $field = '*') {
		return $this->db->get_all($this->table_name, $size, ($page - 1) * $size, $where, $order, $field);
	}
	
	/**
	 * 根据多个主键获取信息
	 * @param array $ids 主键数组
	 * @param string $order 排序
	 * @return array 错误返回false
	 */
	public function getRowsByIds($ids, $order = null, $field='*') {
		$where = array(
			$this->primary_key => $ids	
		);
		return $this->db->get_all($this->table_name, null, 0, $where, $order, $field, false);
	}
	
	/**
	 * 根据条件更新数据
	 * @param array $row
	 * @param array $where
	 * @return int
	 */
	public function update($row, $where) {
		return $this->db->update_by_field($row, $where, $this->table_name);
	}
	
	public function updateById($row, $id) {
		return $this->db->update($id, $row, $this->table_name, $this->primary_key);
	}
	
	/**
	 * 直接更新status状态
	 * @param int $id
	 */
	public function updateStatusById($id, $status) {
		$row = array(
			'status' => $status	
		);
		return $this->updateById($row, $id);
	}
	
	public function deleteById($id) {
		
	}

	/**
	 * 根据条件获取多条数据，并排除重复数据
	 */
	public function getUniqueRows($where = array(), $order = null, $size = null, $offset = 0, $field = '*', $unique='id') {
		return $this->db->get_all($this->table_name, $size, $offset, $where, $order, $field, false, $unique);
	}
}