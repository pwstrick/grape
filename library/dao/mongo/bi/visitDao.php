<?php

/**
 * 接口访问日志记录
 * @author pwstrick
 */
class visitBiMongoDao extends abstractMongoDao {

	public function __construct() {
		parent::__construct();
		$this->db = $this->getBi();
		$visitName = 'visit_log_' . date('Ymd');
		$this->db->selectCollection($visitName);
	}
	
	/**
	 * 更新返回参数
	 * @param string $data
	 * @param string $id
	 */
	public function updateReturn($data, $id) {
		$row = array(
			'api_return_params' => $data
		);
		$this->updateById($row, $id);
	}
	
	/**
	 * 添加日志
	 * @param array $url_attr
	 * @param string $ua
	 * @return array
	 */
	public function logVisit($url_attr, $ua) {
		$ua = $this->_splitUa($ua);
		$row = array_merge($url_attr, $ua);
		$this->insert($row);
		return $row;
	}
	
	/**
	 * 分割参数字符串
	 * @param string $ua
	 * @return array
	 */
	private function _splitUa($ua) {
		$attrs = array();
		if (!empty($ua)) {
			return $attrs;
		}
		$attrs = explode('//', $ua);
		$names = array(
			'os', //设备操作系统
			'device_model', //设备型号
			'market', //应用市场编号
			'app_version', //应用版本号
			'device_imei', //全球唯一设备号
			'device_serial', //设备序列号
			'mobile_mno', //网络运营商
			'net_type' //蜂窝数据类型
		);
		$i = 0;
		$hash = array();
		$length = count($names);
		while ($i < $length) {
			$hash[$names[$i]] = '';
			if (isset($tmp[$i])) {
				$hash[$names[$i]] = $attrs[$i];
			}
			$i++;
		}
		return $hash;
	}
}