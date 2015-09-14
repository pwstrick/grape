<?php
class memberMongoDao extends abstractMongoDao {

	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_user');
	}
	
	public function getMembers() {
		//return $this->getRowById('55668398cd303ab53f8b459d');
		$ids = array(
			'55668398cd303ab53f8b459d',
			'556683be95b290e016a8f8f5'
		);
		$sort = array(
			'create_time' => -1	
		);
		$field = array(
			'openid', 'province'	
		);
		//return $this->getRow($ids, $sort, $field);
		return $this->getRowsByIds($ids, $sort, $field);
	}
	
	public function getMemberCount() {
		$ids = array(
			'55668398cd303ab53f8b459d',
			'556683be95b290e016a8f8f5'
		);
		$mids = array();
		foreach ($ids as $id) {
			$mids[] = new MongoId($id);
		}
		$where = array(
			//'_id' => array('$in' => $mids)
		);
		$field = array(
			'_id','nickname'
		);
		$sort = array(
			'create_time' => -1
		);
		return $this->getRowsLimitByPage($where, $sort);
		//return $this->getRows($where, $sort);
		//return $this->getRow($where, $sort, $field);
		//return $this->getCounts($where, $field, true);
	}
	
	public function updateMember() {
		$where = array(
			'_id' => new MongoId('55668398cd303ab53f8b459d')
		);
		$row = array(
			'sex' => 1,
			'update_time' => time()
		);
		//var_dump($this->getCollection());
		return $this->updateById($row, '55668398cd303ab53f8b459d');
		
	}
}