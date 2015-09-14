<?php
/**
 * @author pwstrick
 */
class weixinJsapiTicketWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_jsapi_ticket');
	}

	/**
	 * 获取微信jsapi_ticket
	 * @return string | null
	 */
	public function getJsapiTicket()
	{
		$accessMongo = InitPHP::getMongoDao('weixinAccessToken', 'mongo/bi');
		$access_token = $accessMongo->getAccessToken();
		if (!$access_token) {
			return null;
		}

		$res = $this->getRow(array(), array('create_time' => -1));
	
		if ($res && is_array($res)) {
			//记录存在，首先判断是否过期
			if (time() - $res['create_time'] > $res['expires_in'] - 1000) {
				//已经过期了，需要重新拉取
				return $this->refreshJsapiTicket($access_token);
			}
			return $res['jsapi_ticket'];
		}
		//记录不存在，从微信端拉取
		return $this->refreshJsapiTicket($access_token);
	}
	
	/**
	 * 从微信刷新jsapi_ticket
	 * @param $access_token
	 */
	public function refreshJsapiTicket($access_token)
	{
		$weixin = InitPHP::getLibrarys('weixin');
		//记录不存在，需要取微信拉取
		$ticket = $weixin->getJsapiTicket($access_token);
		if (!$ticket || !is_array($ticket)) {
			return null;
		}
		$ires = $this->add([
			'jsapi_ticket' => strval($ticket['ticket']),
			'create_time' => time(),
			'expires_in' => intval($ticket['expires_in'])
		]);
		return $ires ? $ticket['ticket'] : null;
	}
}