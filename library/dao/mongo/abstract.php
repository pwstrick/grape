<?php

class abstractMongoDao extends Dao {
	//protected $table_name = null;//表名
	protected $db = null;//数据库
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 获取默认MongoDB默认连接
	 */
	protected function getDefault() {
		return $this->init_nosql('default');
	}
	
	/**
	 * 获取默认MongoDB统计bi连接
	 */
	protected function getBi() {
		return $this->init_nosql('bi');
	}
	
	/**
	 * 获取表数据
	 */
	protected function getCollection() {
		return $this->db->getCollection();
	}

	/**
	 * 添加数据
	 * @param array $row
	 * @param array $options 参数
	 * @return boolean
	 */
	public function insert($row, $options = array()) {
		return $this->db->insert($row, $options);
	}
	
	/**
	 * 获取数量
	 * @param array $where
	 * @param string $field
	 * @param string $distinct
	 * @return int
	 */
	public function getCounts($where = array(), $field = '', $distinct = FALSE) {
		if($distinct && !empty($field)) {
			$rows = $this->db->distinct($where, $field);
			$count = count($rows);
		}else {
			$count = $this->db->findCount($where);
		}
		return $count;
	}
	
	/**
	 * 由于返回的数据key是_id值，所以在这里做处理
	 * @param array $rows
	 * @return array
	 */
	private function getValues($rows) {
		if(empty($rows))
			return array();
		return array_values($rows);
	}
	
	/**
	 * 根据条件获取一条数据
	 * @param array $where
	 * @param array $sort
	 * @param array $field
	 * @return array
	 */
	public function getRow($where = array(), $sort = array(), $field = array()) {
		$row = $this->db->find($where, $sort, 0, 1, $field);
		if(empty($row))
			return array();
		return array_pop($row);
	}

	/**
	 * 根据条件获取多条数据
	 * @param array $where
	 * @param array $sort
	 * @param int $size
	 * @param int $offset
	 * @param array $field
	 * @return array
	 */
	public function getRows($where = array(), $sort = array(), $size = 0, $offset = 0, $field = array()) {
		$rows = $this->db->find($where, $sort, $offset, $size, $field);
		return $this->getValues($rows);
	}
	
	/**
	 * 根据ID获取一条数据
	 * @param string $id
	 */
	public function getRowById($id) {
		$where = array(
			'_id' => new MongoId($id)
		);
		return $this->db->findOne($where);
	}
	
	/**
	 * 分页获取数据
	 * @param array $where
	 * @param array $sort
	 * @param int $page
	 * @param int $size
	 * @param array $field
	 * @return array (0=>数据,1=>数量)
	 */
	public function getRowsLimitByPage($where = array(), $sort = array(), $page = 1, $size = 10, $field = array()) {
		$rows = $this->db->find($where, $sort, ($page - 1) * $size, $size, $field);
		$count = $this->getCounts($where);
		if(empty($rows)) {
			return array(array(), $count);
		}
		return array($this->getValues($rows), $count);
	}
	
	/**
	 * 根据多个id获取信息
	 * @param array $ids id数组
	 * @param string $sort 排序条件 array('age' => -1, 'username' => 1)
	 */
	public function getRowsByIds($ids, $sort=array(), $field=array()) {
		if(empty($ids)) 
			return array();
		$mids = array();
		foreach ($ids as $id) {
			$mids[] = new MongoId($id);
		}
		$where = array('_id' => array('$in' => $mids));
		$rows = $this->db->find($where, $sort, 0, 0, $field);
		return $this->getValues($rows);
	}
	
	/**
	 * 根据条件更新数据
	 * @param array $row
	 * @param array $where
	 * @param array $inc 附加参数
	 * @return boolean
	 */
	public function update($row, $where, $inc = array()) {
		$new_object = array('$set' => $row);
		if(!empty($inc)) {
			$new_object['$inc'] = $inc;
		}
		$affected = $this->db->update($where, $new_object, array("multiple" => true, 'w'=>1));
		return $affected['n'] > 0;
	}
	
	/**
	 * 根据id更新数据
	 * @param array $row
	 * @param string $id
	 * @return boolean
	 */
	public function updateById($row, $id) {
		$where = array(
			'_id' => new MongoId($id)
		);
		return $this->update($row, $where);
	}
	
	public function deleteById($id) {
		
	}
}