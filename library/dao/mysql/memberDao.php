<?php
class memberMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_member';
		$this->db = $this->getDefault();
		$this->primary_key = 'id';
	}
	
	/**
	 * 通过session获取会员信息
	 * @param string $session
	 */
	public function getMemberBySession($session) {
		$where = array(
			'session' => $session	
		);
		return $this->getRow($where);
	}

	/*
	 * demo
	 */
	public function getMembers() {
		//return $this->getRowById(10000013);
		$where = array(
			'uid' => array('>=' => 5)
		);
		$order = array(
			'uid DESC',
			'mobile'	
		);
		$field = array(
			'uid', 'mobile'	
		);
		return $this->getUniqueRows($where, $order, 5, 1, $field, 'is_mother');
		//return $this->getRowsByIds(array(10000012, 10000013), $order, $field);
		//return $this->getRowsLimitByPage($where, $order, 1, 5, $field);
		//return $this->getRows($where, $order, 5, 1, $field);
		//return $this->getRow($where, $order, $field);
		//print_r($this->db->get_all($this->table_name, 20, 0, array(), 'user_id'));
	}
	
	/*
	 * demo
	 */
	public function getUserCount() {
		$where = array(
			'uid' => array('in' => new dbExpr('(select uid from bc_member_info where uid<=10000012)')),
		);
		//return $this->
		return $this->getCounts($where);
		//print_r($this->db->get_all($this->table_name, 20, 0, array(), 'user_id'));
	}
	
	/*
	 * demo
	 */
	public function updateMonther() {
		$row = array(
			'create_time' => time(),
			'is_mother' => new dbExpr('is_mother+1')
		);
		$where = array(
			'uid' => 10000008
		);
		return $this->update($row, $where);
		//return $this->updateById($row, 10000008);
	}
}