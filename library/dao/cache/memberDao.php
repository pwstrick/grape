<?php

class memberCacheDao extends Dao {
	private $table_name = 'member';
	
	public function test() {
		$this->dao->cache->set($this->table_name, 'pwstrick', 0, 'MEM'); //设置缓存
		$test = $this->dao->cache->get($this->table_name, 'MEM'); //获取缓存
		return $test;
	}
}