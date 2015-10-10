<?php

class aclGroupSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_acl_group';
		$this->db = $this->getDefault();
		$this->primary_key = 'id';
	}
	
	/**
	 * 更新权限
	 * @author pwstrick
	 */
	public function access($row) {
		$where = array(
			'group_id' => $row['group_id'],
			'action_id' => $row['action_id']
		);
		return $this->update($row, $where);
	}
	
	/**
	 * 获取该组下的功能列表
	 * @param int $group_id
	 */
	public function getListByGroupId($group_id) {
		$where = array(
			'group_id' => $group_id
		);
		return $this->getRows($where);
	}
	
	/**
	 * 获取组列表下的功能列表
	 * @param array $group_id
	 */
	public function getListByGroupIds($group_ids) {
		$where = array(
			'group_id' => $group_ids
		);
		return $this->getRows($where);
	}
	
	/**
	 * 获取某个组对此aciton的权限
	 */
	public function getGroupAcl($group_id, $action_id) {
		$where = array(
			'group_id' => $group_id,
			'action_id' => $action_id
		);
		return $this->getRow($where);
	}
}