<?php

/**
 * 微信支付封装
 * @author pwstrick
 */
class weixinpayInit
{
	private $APPID = WEIXIN_APPID;
	private $MCHID = WEIXIN_MCHID;
	private $KEY = WEIXIN_KEY;
	private $APPSECRET = WEIXIN_SECRET;
	private $NOTIFY_URL = WEIXIN_NOTIFY_URL;
	private $CURL_TIMEOUT = 30;
	
	private $param = [];
	
	public function __construct(){
		$this->param['appid'] = $this->APPID;
		$this->param['mch_id'] = $this->MCHID;
		$this->param['nonce_str'] = md5(uniqid('baiaimama'));
		$this->param['spbill_create_ip'] = $_SERVER['REMOTE_ADDR']?$_SERVER['REMOTE_ADDR']:'8.8.8.8';
		$this->param['notify_url'] = $this->NOTIFY_URL;
		$this->param['trade_type'] = 'JSAPI';
	}
	
	public function getParam() {
		return $this->param;
	}
	
	/**
	 * 生成jsapi需要调用的参数
	 */
	public function getJsapiParam(){
		$param = [
		'appId' => $this->APPID,
		'timeStamp' => time(),
		'nonceStr' => $this->param['nonce_str'],
		'package' => 'prepay_id='.$this->param['prepay_id'],
		'signType' => 'MD5'
				];
	
		$str = [];
		foreach($param as $k=>$v){
			if(!empty($v)){
				$str[] = "{$k}={$v}";
			}
		}
		sort($str);
		$unsignKey = join('&', $str).'&key='.$this->KEY;
		$sign = strtoupper(md5($unsignKey));
		$param['paySign'] = $sign;
		return $param;
	}
	
	/**
	 * 设置参数
	 * @param string $key
	 * @param string $value
	 */
	public function setParam($key, $value){
		$key = strval($key);
		$value = strval($value);
		if(!$key || !$value){
			return ;
		}
		$this->param[$key] = $value;
	}
	
	/**
	 * 利用统一支付接口获取预支付订单id
	 */
	public function getPrepayId(){
		$url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
		$this->bodyArr();
		$wx = new Weixin();
		$xml = $this->arrayToXml();
		$msg = $wx->rawpost($url, $xml);
		
		$msgArr = $wx->parseMsg($msg);
		
		if(!$msgArr || !is_object($msgArr)){
			throw new \Exception('预支付订单申请请求失败');
		}
		//print_r($msgArr);exit();
		if($msgArr->return_code=='SUCCESS'){
			$this->param['prepay_id'] = $msgArr->prepay_id;
		} else {
			throw new \Exception('无法创建预支付订单');
		}
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
	 * 生成请求消息体
	 */
	private function bodyArr(){
		$str = [];
		foreach($this->param as $k=>$v){
			if(!empty($v)){
				$str[] = "{$k}={$v}";
			}
		}
		sort($str);
		
		$unsignKey = join('&', $str).'&key='.$this->KEY;
		$sign = strtoupper(md5($unsignKey));
		$this->param['sign'] = $sign;
	}
	
	/**
	 * 将数组转换成xml
	 * @param $arr 数组对象
	 */
	public function arrayToXml(){
		$body = '<xml>';
		foreach($this->param as $k=>$v){
			$body .= "<{$k}><![CDATA[{$v}]]></{$k}>";
		}
		$body .= '</xml>';
		return $body;
	}
	
	/**
	 * 将数组转换成xml
	 * @param $arr 数组对象
	 */
	public function arrayToXmlWithout(){
		$str = '<xml>';
		foreach($this->param as $k=>$v) {
// 			if($k == 'body') {
// 				$str .= sprintf('<body>%s</body>', $v);
// 			}else {
// 				$str .= sprintf('<%s>%s</%s>', $k, $v, $k);
// 			}
			$str .= sprintf('<%s>%s</%s>', $k, $v, $k);
		}
		$str .= '</xml>';
		return $str;
	}
	
	/**
	 * 验证消息签名
	 * @param $msg 消息体，对象类型
	 * @return bool
	 */
	public function checkSign($msg){
		$str = [];
		foreach($msg as $k=>$v){
			if($k=='sign') continue;
			$str[] = "{$k}={$v}";
		}
		sort($str);
		$unsignKey = join('&', $str).'&key='.$this->KEY;
		$calSign = strtoupper(md5($unsignKey));
		return $msg->sign==$calSign ? true : false;
	}
	
	/**
	 * 异步推送通知相应体
	 * @param $return_code 返回状态码 SUCCESS/FAIL
	 * @param $return_msg 为原因
	 */
	public function notifyXml($return_code = 'SUCCESS', $return_msg = 'OK'){
		$xml = "<xml>";
		$xml .= "<return_code><![CDATA[{$return_code}]]></return_code>";
		$xml .= "<return_msg><![CDATA[$return_msg]]></return_msg>";
		$xml .= "</xml>";
		
		$notifyMongo = new WeixinPayNotifyResponse();
		$notifyMongo->logPayNotifyResponse($xml);
		echo $xml;
		die;
	}
}