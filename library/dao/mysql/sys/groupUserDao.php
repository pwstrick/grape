<?php

class groupUserSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_group_user';
		$this->db = $this->getDefault();
		$this->primary_key = 'id';
	}

	/**
	 * 根据status获取分组列表
	 * @param int $status
	 * @author pwstrick
	 */
	public function getListByGroupId($group_id, $status=constHelper::STATUS_NORMAL) {
		$where = array(
			'status' => $status,
			'group_id' => $group_id
		);
		return $this->getRows($where);
	}
	
	/**
	 * 根据用户ID获取所在的组
	 * @param int $uid
	 */
	public function getListByUid($uid) {
		$where = array(
			'status' => constHelper::STATUS_NORMAL,
			'user_id' => $uid
		);
		return $this->getRows($where);
	}
}