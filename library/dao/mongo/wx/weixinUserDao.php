<?php
/**
 * @author pwstrick
 */
class weixinUserWxMongoDao extends abstractMongoDao {
	public function __construct() {
		parent::__construct();
		$this->db = $this->getDefault();
		$this->db->selectCollection('weixin_user');
	}

	/**
	 * 根据openid获取信息
	 * @param string $openid
	 * @return array
	 */
	public function getUserByOpenId($openid) {
		$where = array(
			'openid' => $openid
		);
		return $this->getRow($where);
	}
	
	/**
	 * 根据openid更新信息
	 * @param string $openid
	 * @return bool
	 */
	public function updateByOpendId($row, $openid) {
		$where = array(
			'openid' => $openid
		);
		return $this->mupdate($row, $where);
	}
	
	/**
	 * 根据关注openid从微信端更新用户资料
	 * @param $openid 关注者openid
	 * @return bool
	 */
	public function updateUserByOpenid($openid) {
		$openid = strval($openid);
		if (!$openid) return false;
		
		$user = $this->getRow(array('openid' => $openid));
		$weixin = InitPHP::getLibrarys('weixin');
		//始终从微信获取资料
		$accessToken = InitPHP::getMongoDao('weixinAccessToken', 'mongo/bi');
		$access_token = $accessToken->getAccessToken();
		$wxuser = [];
		if ($access_token) {
			//获取到关注用户的信息
			$wxuser = $weixin->getUserinfoByAccessToken($access_token, $openid);
			if (!$wxuser || !is_array($wxuser)) {
				$wxuser = [];
			}
		}
		
		$now = time();
		$tmpuser = ['openid' => $openid, 'update_time' => $now];
		$tmpuser = array_merge($wxuser, $tmpuser);
		if (!$user || !is_array($user)) {
			//未关注用户，重新生成用户记录
			$tmpuser['create_time'] = $now;
			return $this->add($tmpuser);
		}
		//已关注用户只需要更新用户资料即可
		return $this->mupdate($tmpuser, ['openid' => $openid]);
	}
}