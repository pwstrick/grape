<?php
/**
 * @author pwstrick
 */
class weixinPayNotifyResponseWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_pay_notify_response');
	}
	
	/**
	 * 记录微信支付异步推送回复
	 * @param $data
	 */
	public function logPayNotifyResponse($data){
		$this->add(array(
			'data' => $data,
			'create_time' => time(),
			'readable_time' => date('y-m-d H:i:s')
		));
	}
}