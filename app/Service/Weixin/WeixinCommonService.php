<?php
class WeixinCommonService extends Service {
	public function init(){
		$this->setting = $this->config('connect');
	}
	public function getSignature(){
		$url = $_SERVER['HTTP_REFERER'];
		if(!$url){
			return array();
		}
		$jsapiTicket = $this->getJsApiTicket();
	
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		$config = (array)$this->setting['weixin'];
	
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	
		$signature = sha1($string);
	
		$signPackage = array(
		  "appId"     => $config['appkey'],
		  "nonceStr"  => $nonceStr,
		  "timestamp" => $timestamp,
		  "url"       => $url,
		  "signature" => $signature
		);
		return $signPackage; 
    }
	public function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
  	}
	
	public function getJsApiTicket(){
		$ticket = '';
		
		$where = array();
		$where['cname'] = 'weixin_jsapi_ticket';
		$cacheData = $this->model('FoundationSyscache')->where($where)->find();
		if(!$cacheData || $this->getTime() > $cacheData['expire_time']){
		
		 	$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket'; 
			
			$param = array();
			$param["offset_type"] = 1;
			$param["type"] = 'jsapi';
			$param["access_token"] = $this->getAccessToken();
			
			$response = $this->helper('curl')->init($url)->data($param)->fetch();
			//取出openid
			$response = json_decode($response,true);
			if(isset($response['ticket'])){
				if($cacheData){
					$where = array();
					$where['cname'] = 'weixin_jsapi_ticket';
					$setarr =array(
						'cdata'=>$response['ticket']
					);
					$setarr['lastupdate'] = $this->getTime();
					$setarr['expire_time'] = $setarr['lastupdate']+$response['expires_in'];
					$this->model('FoundationSyscache')->data($setarr)->where($where)->save();
				}else{
					$setarr =array(
						'cname'=>'weixin_jsapi_ticket',
						'cdata'=>$response['ticket'],
						'dateline'=>$this->getTime(),
					);
					$setarr['lastupdate'] = $setarr['dateline'];
					$setarr['expire_time'] = $setarr['lastupdate']+$response['expires_in'];
					$this->model('FoundationSyscache')->data($setarr)->add();
				}
				$ticket = $response['ticket'];
			}
		}else{
			$ticket = $cacheData['cdata'];
		}
		
		return $ticket;
	}
	
	
	public function getAccessToken(){
		$accessToken = '';
		
		$where = array();
		$where['cname'] = 'weixin_access_token';
		$cacheData = $this->model('FoundationSyscache')->where($where)->find();
		if(!$cacheData || $this->getTime() > $cacheData['expire_time']){
		
		 	$url = 'https://api.weixin.qq.com/cgi-bin/token'; 
			
			$param = array();
			
			$config = (array)$this->setting['weixin'];
			
			$param["appid"] = $config['appkey'];
			$param["secret"] = $config['secret'];
			$param["grant_type"] = "client_credential";
			
			$response = $this->helper('curl')->init($url)->data($param)->fetch();
			//取出openid
			$response = json_decode($response,true);
			if(isset($response['access_token'])){
				if($cacheData){
					$where = array();
					$where['cname'] = 'weixin_access_token';
					$setarr =array(
						'cdata'=>$response['access_token']
					);
					$setarr['lastupdate'] = $this->getTime();
					$setarr['expire_time'] = $setarr['lastupdate']+$response['expires_in'];
					$this->model('FoundationSyscache')->data($setarr)->where($where)->save();
				}else{
					$setarr =array(
						'cname'=>'weixin_access_token',
						'cdata'=>$response['access_token'],
						'dateline'=>$this->getTime(),
					);
					$setarr['lastupdate'] = $setarr['dateline'];
					$setarr['expire_time'] = $setarr['lastupdate']+$response['expires_in'];
					$this->model('FoundationSyscache')->data($setarr)->add();
				}
				$accessToken = $response['access_token'];
			}
		}else{
			$accessToken = $cacheData['cdata'];
		}
		
		return $accessToken;
	}
}