<?php

class abstractSearchDao extends Dao {
	//protected $table_name = null;//表名
	protected $db = null;//数据库
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 获取Sphinx默认连接
	 */
	protected function getDefault() {
		return $this->init_search('default');
	}
}