<?php
/**
 *
 * 第三方平台
 *
 * 权限
 *
 */
class  AuthorityConnectService extends Service {
	protected $setting;
	public function init(){
		$this->setting = (array)$this->config('connect');
		if(isset($this->setting['weixin'])){
			$this->setting = (array)$this->setting['weixin'];
		}
		if(defined('__SUB_HOST__') && isset($this->setting[__SUB_HOST__])){
			$this->setting =  (array)$this->setting[__SUB_HOST__];
		}
	}
	
	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function getLoginUrl($redirectUrl,$agent = 0 )
	{		
		$wechat = array();
		
		$wechat['appid'] = $this->setting['appkey'];
		$wechat["redirect_uri"] = $redirectUrl;
		$wechat["response_type"] = "code";
		//snsapi_base登录信息
		//snsapi_userinfo 静默授权
		$wechat["scope"] = "snsapi_base,snsapi_userinfo";
		$wechat["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->ToUrlParams($wechat);
		
		$agent = intval($agent);
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
		if(!$agent){
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false ){
				$url = 'https://open.weixin.qq.com/connect/qrconnect?';
			}
		}
		//var_dump($bizString);
		//var_dump($url);
		//die();
		return $url.$bizString;
	}
	
	
	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function getOpenid($code)
	{
		if(!$code){
			return -1;
		}
		
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
		
		$param = array();
		
		
		$param["appid"] = $this->setting['appkey'];
		$param["secret"] = $this->setting['secret'];
		$param["code"] = $code;
		$param["grant_type"] = "authorization_code";
		
		$response = $this->helper('curl')->init($url)->data($param)->fetch();
		if(!$response){
		}
		
		$userinfo = array();
		//取出openid
		$response = json_decode($response,true);
		if(isset($response['access_token']) && isset($response['openid'])){
		
			$userinfo = $this->getUserInfo($response['access_token'],$response['openid']);
		}else{
			//$this->log($response);
		}
		
		return $userinfo;
	}
	
	
	/**
	 * 
	 * 获取用户信息
	 * @param array $accessToken
	 * @param array $openId
	 * 
	 * @return 返回已经拼接好的字符串
	 */
	public function getUserInfo($accessToken,$openId){
		
		$url = 'https://api.weixin.qq.com/sns/userinfo';
		
		$param = array();
		$param['access_token'] = $accessToken;
		$param['openid'] = $openId;
		$param['lang'] = 'zh_CN';
		
		$response = $this->helper('curl')->init($url)->data($param)->fetch();
		if(!$response){
		}
		
		return json_decode($response,true);
	}
	
	/**
	 * 
	 * 拼接签名字符串
	 * @param array $urlObj
	 * 
	 * @return 返回已经拼接好的字符串
	 */
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
}