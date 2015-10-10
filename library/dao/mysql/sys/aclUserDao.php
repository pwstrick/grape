<?php

class aclUserSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_acl_user';
		$this->db = $this->getDefault();
		$this->primary_key = 'id';
	}
	
	/**
	 * 更新权限
	 * @author pwstrick
	 */
	public function access($row) {
		$where = array(
			'user_id' => $row['user_id'],
			'action_id' => $row['action_id']
		);
		return $this->update($row, $where);
	}
	
	/**
	 * 获取该组下的功能列表
	 * @param int $user_id
	 */
	public function getListByUserId($user_id) {
		$where = array(
			'user_id' => $user_id
		);
		return $this->getRows($where);
	}
	
	/**
	 * 获取用户对此aciton的权限
	 */
	public function getHisAcl($user_id, $action_id) {
		$where = array(
			'user_id' => $user_id,
			'action_id' => $action_id
		);
		return $this->getRow($where);
	}
}