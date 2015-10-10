<?php

class moduleSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_module';
		$this->db = $this->getDefault();
		$this->primary_key = 'module_id';
	}
	
	/**
	 * 根据status获取模块列表
	 * @param int $status
	 * @author pwstrick
	 */
	public function getListByStatus($status=constHelper::STATUS_NORMAL) {
		$where = array(
			'status' => $status
		);
		$order = 'sort';
		return $this->getRows($where, $order);
	}
	
	/**
	 * 更新排序
	 * @param int $id
	 * @param int $sort
	 */
	public function updateSort($id, $sort) {
		$row = array(
			'sort' => $sort
		);
		return $this->updateById($row, $id);
	}
}