<?php

class actionSysMysqlDao extends abstractMysqlDao {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'bc_sys_action';
		$this->db = $this->getDefault();
		$this->primary_key = 'action_id';
	}

	/**
	 * 根据status获取模块列表
	 * @param string $module_key 模块名字
	 * @param int $status
	 * @author pwstrick
	 */
	public function getListByStatus($module_key, $status=constHelper::STATUS_NORMAL) {
		$where = array(
			'module_key' => $module_key,
			'status' => $status
		);
		$order = 'sort';
		return $this->getRows($where, $order);
	}
	
	/**
	 * 更新排序
	 * @param int $id
	 * @param int $sort
	 */
	public function updateSort($id, $sort) {
		$row = array(
			'sort' => $sort
		);
		return $this->updateById($row, $id);
	}
	
	/**
	 * 获取可用的菜单选项
	 * @param string $module_key
	 */
	public function getMenuList($module_key='') {
		$where = array(
			'status' => constHelper::STATUS_NORMAL,
			'action_menu' => 1
		);
		if(!empty($module_key)) {
			$where['module_key'] = $module_key;
		}
		$order = 'sort';
		return $this->getRows($where, $order);
	}
	
	/**
	 * 根据module_key和action_key获取一条数据
	 * @param string $mkey
	 * @param string $akey
	 */
	public function getOneByKey($mkey, $akey) {
		$where = array(
			'status' => constHelper::STATUS_NORMAL,
			'module_key' => $mkey,
			'action_key' => $akey
		);
		return $this->getRow($where);
	}
}