<?php
/**
 * @author pwstrick
 */
class weixinPushLogWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_push_log');
	}
	
	/**
	 * 记录微信的推送日志
	 * @param $msg string 微信推送日志原文
	 * @return bool
	 */
	public function logWeixinPush($msg)
	{
		$msg = strval($msg);
		$time = time();
		$request_method = getenv('REQUEST_METHOD');
		return $this->add(array(
				'msg' => $msg,
				'request_method' => $request_method,
				'request_data' => $_REQUEST,
				'carete_time' => $time,
				'readable_create_time' => date('Y-m-d H:i:s', $time)
		));
	}
}