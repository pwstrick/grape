<?php
/**
 * @author pwstrick
 */
class weixinPayNotifyWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_pay_notify');
	}
	
	/**
	 * 记录微信支付异步推送
	 * @param $data
	 */
	public function logPayNotify($data){
		$this->add(array(
			'data' => $data,
			'create_time' => time(),
			'readable_time' => date('y-m-d H:i:s')
		));
	}
}