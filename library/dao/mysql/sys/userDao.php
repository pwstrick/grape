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
}