<?php

class userSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_user';
		$this->db = $this->getDefault();
		$this->primary_key = 'id';
	}
	
	/**
	 * 登录验证
	 * @param string $account
	 * @param string $pwd
	 */
	public function login($account, $pwd) {
		$where = array(
			'account' => $account,
			'pwd' => $pwd
		);
		return $this->getRow($where);
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
		return $this->getRows($where);
	}
	
	/**
	 * 更新密码
	 * @param string $new
	 * @param string $old
	 * @param int $id 
	 */
	public function updatePwd($new, $old, $id) {
		$where = array(
			'pwd' => $old,
			'id' => $id
		);
		$row = array(
			'pwd' => $new	
		);
		return $this->update($row, $where);
	}
}