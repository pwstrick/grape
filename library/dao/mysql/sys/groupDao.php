<?php

class groupSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_group';
		$this->db = $this->getDefault();
		$this->primary_key = 'group_id';
	}
	
	/**
	 * 根据status获取分组列表
	 * @param int $status
	 * @author pwstrick
	 */
	public function getListByStatus($status=constHelper::STATUS_NORMAL) {
		$where = array(
			'status' => $status
		);
		$order = 'group_id DESC';
		return $this->getRows($where, $order);
	}
}