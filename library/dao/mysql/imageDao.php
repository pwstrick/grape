<?php
class imageMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_image';
		$this->db = $this->getDefault();
		$this->primary_key = 'id';
	}
	
	/**
	 * 验证图片指纹是否重复
	 * @author Pwstrick
	 * @param string $imageMd
	 * @return array
	 */
	public function checkHash($imageMd) {
		$where = array('hash' => $imageMd);
		return $this->getRow($where);
	}
}