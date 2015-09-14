<?php

/**
 * 微信封装
 * @author pwstrick
 */
class weixinInit
{
	private $appId = WEIXIN_APPID;
	private $appSecret = WEIXIN_SECRET;
	
	private $token = WEIXIN_TOKEN;
	
	/**
	 * 获取公众号appid
	 * @return string
	 */
	public function getAppId()
	{
		return $this->appId;
	}
	
	/**
	 * 验证签名是否正确，一般是初次做校验
	 * @return bool
	 */
	public function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
	
		$token = $this->token;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
	
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 刷新全局通用凭证access_token，此token与网页端
	 * 获取用户信息时access_token不一样，注意区分
	 *
	 * @return bool|string
	 */
	public function refreshAccessToken()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token';
		$param = array(
				'grant_type' => 'client_credential',
				'appid' => $this->appId,
				'secret' => $this->appSecret
		);
		$result = $this->request($url, $param);
		$res = json_decode($result, true);
		if (isset($res['errcode'])) {
			return false;
		}
		return $res;
	}
	
	/**
	 * 读取原始请求数据
	 * @return string
	 */
	public function getRawMsg()
	{
		return file_get_contents('php://input');
	}
	
	/**
	 * 模拟发送请求
	 * @param $url 请求的url地址
	 * @param array $param 请求参数
	 * @param string $method 请求方法
	 * @return mixed 请求返回
	 */
	public function request($url, $param = array(), $method = 'get')
	{
		$resource = curl_init();
		$queryString = '';
		if (!empty($param) && is_array($param)) {
			$seg = array();
			foreach ($param as $k => $v) {
				$seg[] = "{$k}={$v}";
			}
			$queryString = join('&', $seg);
		}
		if (strtolower($method) == 'post') {
			curl_setopt($resource, CURLOPT_POSTFIELDS, $queryString);
			curl_setopt($resource, CURLOPT_POST, true);
		}
		if (strtolower($method) == 'get') {
			$url .= '?' . $queryString;
		}
		curl_setopt($resource, CURLOPT_URL, $url);
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($resource, CURLOPT_SSL_VERIFYHOST, false);
		$data = curl_exec($resource);
		curl_close($resource);
		return $data;
	}
	
	/**
	 * 原始原始POST
	 * @param $url 请求的url地址
	 * @param $raw 原始数据，可以为字符串或数组
	 * @return mixed 返回请求值
	 */
	public function rawpost($url, $raw)
	{
		$resource = curl_init();
		curl_setopt($resource, CURLOPT_POST, true);
		curl_setopt($resource, CURLOPT_URL, $url);
		curl_setopt($resource, CURLOPT_POSTFIELDS, $raw);
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($resource, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($resource, CURLOPT_HTTPHEADER, array('Expect:'));
		$data = curl_exec($resource);
		curl_close($resource);
		return $data;
	}
	
	/**
	 * 解析接收到的消息
	 * @param string $msg 消息体
	 * @return bool|SimpleXMLElement
	 */
	public function parseMsg($msg = '')
	{
		if (!$msg || empty($msg)) {
			return false;
		}
		$msgObj = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($msgObj === false || !($msgObj instanceof \SimpleXMLElement)) {
			return false;
		}
		return $msgObj;
	}
	
	/**
	 * 创建一个需要通过微信的OAuth2.0认证的服务url
	 * @param $url 服务号需要认证访问的url
	 * @param $scope string snsapi_userinfo | snsapi_base
	 *      snsapi_userinfo 可以用来获取用户信息
	 *      snsapi_base 可以用来获取openid
	 * @param string $state 自定义状态值
	 *      此处约定为from_weixin代表是从微信认证过来，一般无需轻易变化
	 * @return string 返回认证url地址
	 */
	public function createAuthUrl($url, $scope = 'snsapi_base', $state = 'from_weixin')
	{
		$url = strval($url);
		$authUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
		/**
		 * 此处有大坑，请不要打乱param的顺序
		 * 否则微信认证界面会出现白屏
		 */
		$param = array(
				'appid' => $this->appId,
				'redirect_uri' => urlencode($url),
				'response_type' => 'code',
				'scope' => $scope,
				'state' => $state
		);
	
		$seg = array();
		foreach ($param as $k => $v) {
			$seg[] = "{$k}={$v}";
		}
		return $authUrl . '?' . join('&', $seg) . '#wechat_redirect';
	}
	
	/**
	 * 创建一条图文消息
	 * @param $fromUserName 发送的用户，一般为我们自己的用户名
	 * @param $toUserName 接收的用户，一般为openid
	 * @param array $items 具体消息，这是一个二维数组，数组每一维结构如下
	 *      array(
	 *          Title => 图文消息标题
	 *          Description => 图文消息描述
	 *          PicUrl => 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
	 *          Url => 点击图文消息跳转链接
	 *      )
	 * @param string snsapi_userinfo | snsapi_base
	 *      snsapi_userinfo 可以用来获取用户信息
	 *      snsapi_base 可以用来获取openid
	 * @return string 返回消息体
	 */
	public function createTuWenMsg($fromUserName, $toUserName, $items = array(), $scope = 'snsapi_base', $state = 'from_weixin')
	{
		if (!is_array($items)) {
			return '';
		}
		$count = count($items);
		$its = '';
		foreach ($items as $item) {
			$Url = $this->createAuthUrl($item['Url'], $scope, $state);
			$its .= <<<ITEMTPL
<item>
<Title><![CDATA[{$item['Title']}]]></Title>
<Description><![CDATA[{$item['Description']}]]></Description>
<PicUrl><![CDATA[{$item['PicUrl']}]]></PicUrl>
<Url><![CDATA[{$Url}]]></Url>
</item>
ITEMTPL;
		}
	
		$msg = <<<MSG
<xml>
<ToUserName><![CDATA[{$toUserName}]]></ToUserName>
<FromUserName><![CDATA[{$fromUserName}]]></FromUserName>
<CreateTime>12345678</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>{$count}</ArticleCount>
<Articles>
{$its}
</Articles>
</xml>
MSG;
	return $msg;
	}
	
	public function createRawTuWenMsg($fromUserName, $toUserName, $items = array())
	{
		if (!is_array($items)) {
			return '';
		}
		$count = count($items);
		$its = '';
		foreach ($items as $item) {
			$its .= <<<ITEMTPL
<item>
<Title><![CDATA[{$item['Title']}]]></Title>
<Description><![CDATA[{$item['Description']}]]></Description>
<PicUrl><![CDATA[{$item['PicUrl']}]]></PicUrl>
<Url><![CDATA[{$item['Url']}]]></Url>
</item>
ITEMTPL;
		}
	
		$msg = <<<MSG
<xml>
<ToUserName><![CDATA[{$toUserName}]]></ToUserName>
<FromUserName><![CDATA[{$fromUserName}]]></FromUserName>
<CreateTime>12345678</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>{$count}</ArticleCount>
<Articles>
{$its}
</Articles>
</xml>
MSG;
	return $msg;
	}
	
	/**
	 * 创建一条纯文本消息
	 *
	 * @param $fromUserName 发送方用户
	 * @param $toUserName 接收方用户
	 * @param string $msg 消息体
	 * @return string 返回消息体
	 */
	public function createWenbenMsg($fromUserName, $toUserName, $msg = '')
	{
		$msg = strval($msg);
		if (!$msg) {
			$msg = '感谢您关注小爱，请点击菜单了解更多';
		}
		$createTime = time();
		$txtMsg = <<<TXTMSG
<xml>
<ToUserName><![CDATA[{$toUserName}]]></ToUserName>
<FromUserName><![CDATA[{$fromUserName}]]></FromUserName>
<CreateTime>{$createTime}</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[{$msg}]]></Content>
</xml>
TXTMSG;
		return $txtMsg;
	}
	
	/**
	 * 通过code换取网页授权access_token
	 * @param $code 授权成功后微信传递给服务器的code
	 * @param array|bool 如果成功信息中也会包含openid
	 */
	public function getWebAccessToken($code = '')
	{
		if (!$code || !is_string($code)) {
			return false;
		}
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
		$param = array(
				'appid' => $this->appId,
				'secret' => $this->appSecret,
				'code' => $code,
				'grant_type' => 'authorization_code'
		);
		$result = $this->request($url, $param);
		$res = json_decode($result, true);
		if (isset($res['errcode'])) {
			return false;
		}
		return $res;
	}
	
	/**
	 * 刷新用户认证时网页授权token
	 *
	 * @param $refresh_token
	 * @return bool|string
	 */
	public function refreshWebAccessToken($refresh_token)
	{
		$url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
		$param = array(
				'appid' => $this->appId,
				'grant_type' => refresh_token,
				'refresh_token' => $refresh_token
		);
		$result = $this->request($url, $param);
		$res = json_decode($result, true);
		if (isset($res['errcode'])) {
			return false;
		}
		return $res;
	}
	
	/**
	 * 获取用户信息接口，通过网页授权access_token
	 *
	 * @param $web_access_token 网页用户授权用access_token
	 * @param $openid 网页用户的openid
	 * @return bool|string
	 */
	public function getUserinfoByWebAccessToken($web_access_token, $openid)
	{
		if (!$web_access_token || !$openid) {
			return null;
		}
	
		$url = 'https://api.weixin.qq.com/sns/userinfo';
		$param = array(
				'access_token' => $web_access_token,
				'openid' => $openid,
				'lang' => 'zh_CN'
		);
		$result = $this->request($url, $param);
		$res = json_decode($result, true);
		//print_r($res);
		if (isset($res['errcode'])) {
			return null;
		}
		return $res;
	}
	
	/**
	 * 通过全局access_token获取用户信息，此接口只能获取关注用户信息
	 * 非关注用户无法获取
	 *
	 * @param $access_token 全局access_token
	 * @param $openid 用户openid
	 * @return array | null
	 */
	public function getUserinfoByAccessToken($access_token, $openid)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info';
		$param = array(
				'access_token' => $access_token,
				'openid' => $openid,
				'lang' => 'zh_CN'
		);
		$result = $this->request($url, $param);
		$res = json_decode($result, true);
		if (isset($res['errcode']) && $res['errcode'] > 0) {
			return null;
		}
		return $res;
	}
	
	/**
	 * 获取永久二维码需要的ticket
	 * @param $access_token access_token
	 * @param $sceneId 场景值id
	 * @return bool|mixed
	 */
	public function getQrticket($access_token, $sceneId)
	{
		$access_token = strval($access_token);
		$sceneId = intval($sceneId);
		if (!$access_token || !$sceneId) {
			return false;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token;
		$raw = array(
				'action_name' => 'QR_LIMIT_SCENE',
				'action_info' => array(
						'scene' => array(
								'scene_id' => $sceneId
						)
				)
		);
		$json = json_encode($raw);
		$res = $this->rawpost($url, $json);
		if ($res === false) {
			return false;
		}
		$qrres = json_decode($res, true);
		if (isset($qrres['errcode'])) {
			return false;
		}
		return $qrres;
	}
	
	/**
	 * 根据ticket下载二维码图片，一般情况下根据
	 * getQrticket方法即可以获取二维码链接，无需重复下载
	 *
	 * @param $ticket
	 * @return mixed
	 */
	public function getQrcode($ticket)
	{
		$ticket = strval($ticket);
		$ticket = urlencode($ticket);
		$url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';
		$param = array(
				'ticket' => $ticket
		);
		return $this->request($url, $param);
	}
	
	/**
	 * 自定义菜单
	 * @param $menu array 自定义菜单的结构
	 * @param $access_token string token的值
	 */
	public function customMenu($menu, $access_token)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
		$result = $this->rawpost($url, $menu);
		$res = json_decode($result, true);
		return $res['errcode'] != 0 ? false : true;
	}
	
	/**
	 * 查询自定义的菜单
	 * @param $access_token 通用access_token
	 * @return mixed 返回菜单结构或错误
	 */
	public function getMenu($access_token)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get';
		$param = array(
				'access_token' => $access_token
		);
		$res = $this->request($url, $param);
		return json_decode($res, true);
	}
	
	/**
	 * 删除自定义菜单统一接口
	 * @param $access_token
	 * @return bool
	 */
	public function deleteMenu($access_token)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete';
		$param = array(
				'access_token' => $access_token
		);
		$result = $this->request($url, $param);
		$res = json_decode($result, true);
		return $res['errcode'] != 0 ? false : true;
	}
	
	/**
	 * 根据模板编号获取模板id
	 * @param $access_token 全局公共token
	 * @param $templateCode
	 */
	public function getTemplateMsgId($access_token, $templateCode)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $access_token;
		$res = $this->rawpost($url, json_encode(array(
				'template_id_short' => $templateCode
		)));
		$result = json_decode($res, true);
		if ($result['errcode'] == 0 && isset($result['template_id'])) {
			return $result['template_id'];
		}
	
		return null;
	}
	
	/**
	 * 发送模板消息
	 *
	 * @param $access_token 全局token
	 * @param $openid 接收消息的公众号id
	 * @param $templateId 模板id
	 * @param $jumpUrl 点击模板消息跳转的url
	 * @param $data 模板消息数据
	 * @param $topColor 模板头部颜色
	 */
	public function sendTemplateMsg($access_token, $openid, $templateId, $jumpUrl, $data, $topColor = '#FF0000')
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
		$dataBody = [
		'touser' => $openid,
		'template_id' => $templateId,
		'url' => $jumpUrl,
		'topcolor' => $topColor,
		'data' => $data
		];
		$res = $this->rawpost($url, json_encode($dataBody));
		$result = json_decode($res, true);
		if ($result && $result['errcode'] == 0 && isset($result['msgid'])) {
			return $result['msgid'];
		}
		return false;
	}
	
	/**
	 * 通过access_token获取jsapi_ticket
	 * @param $access_token
	 * @return string | null
	 */
	public function getJsapiTicket($access_token)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
		$param = [
		'access_token' => $access_token,
		'type' => 'jsapi'
				];
		$res = $this->request($url, $param);
		$result = json_decode($res, true);
		if (isset($result['errcode']) && $result['errcode'] == 0 && isset($result['ticket'])) {
			return $result;
		}
		return null;
	}
	
	/**
	 * 获取素材列表
	 * @param $access_token
	 * @param string $type 素材类型
	 * @param int $offset 获取素材的偏移点
	 * @param int $count 素材总量
	 * @return []
	 */
	public function getMaterial($access_token, $type = 'news', $offset = 0, $count = 20)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$access_token;
		$param = [
		'type' => $type,
		'offset' => $offset,
		'count' => $count
		];
		$res = $this->rawpost($url, json_encode($param));
		$result = json_decode($res, true);
		if (isset($result['errcode']) && $result['errcode']!=0) {
			return null;
		}
		return $result;
	}
	
	/**
	 * 根据access_token获取具体的素材
	 * @param $access_token
	 * @param $mediaId 素材ID
	 * @return []
	 */
	public function getMaterialMedia($access_token, $mediaId){
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$access_token;
		$param = [
		'media_id' => $mediaId
		];
		$res = $this->rawpost($url, json_encode($param));
		//todo 此时$res是图片的二进制数据流，可以保存本地
	}
}