<?php

class memberSearchDao extends abstractSearchDao {
	
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
	}
	
	/**
	 * demo
	 */
	public function search() {
		$this->db->setMatchMode(SPH_MATCH_PHRASE);
		$this->db->setMaxQueryTime(300);
		$this->db->setSelect("*");
		$lon = 121;
		$lat = 31;
		$this->db->SetGeoAnchor('latitude', 'longtitude', (float)deg2rad($lat), (float) deg2rad($lon));
		$this->db->SetSortMode(SPH_SORT_EXTENDED, '@geodist asc'); // 按距离正向排序
		$this->db->SetLimits(0,10);
		$res = $this->db->query('都', 'mysql'); #[愚人]关键字，[mysql]数据源source
		print_r($res);
	}
}