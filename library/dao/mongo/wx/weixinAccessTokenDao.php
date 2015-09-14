<?php
/**
 * @author pwstrick
 */
class weixinAccessTokenWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_access_token');
	}
	
	/**
	 * 获取access_token
	 * @return string | null
	 */
	public function getAccessToken() {
		$res = $this->getRow(array(), array('create_time' => -1));

		//数据库中没有记录，说明还没有获取过access_token
		if (!$res || !is_array($res)) {
			$accesstoken = $this->refreshAccessToken();
			return $accesstoken ? $accesstoken : null;
		}
	
		//记录存在则检测是否过期，过期了重新拉取更新
		if (time() - $res['create_time'] > $res['expires_in'] - 1000) {
			$accesstoken = $this->refreshAccessToken();
			return $accesstoken ? $accesstoken : null;
		}
	
		return $res['access_token'];
	}
	
	/**
	 * 从微信刷新access_token
	 *
	 * @return null
	 */
	public function refreshAccessToken()
	{
		//为空说明没有记录，需要生成一条记录
		$weixin = InitPHP::getLibrarys('weixin');
		$res = $weixin->refreshAccessToken();
		if ($res && is_array($res)) {
			$ires = $this->add(array(
				'access_token' => $res['access_token'],
				'create_time' => time(),
				'expires_in' => $res['expires_in']
			));
			return $ires ? $res['access_token'] : null;
		}
		return null;
	}
}