<?php

/**
 * @author pwstrick
 */
class weixinReplyLogWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_reply_log');
	}

	/**
	 * 记录对微信消息的被动回复
	 * @param $msg 回复消息体
	 * @param string $type 回复类型
	 */
	public function logWeixinReply($msg, $type = "tuwen")
	{
		$msg = strval($msg);
		$time = time();
		return $this->add(array(
				'msg' => $msg,
				'msg_type' => $type,
				'create_time' => $time,
				'readable_create_time' => date('Y-m-d H:i:s', $time)
		));
	}
}