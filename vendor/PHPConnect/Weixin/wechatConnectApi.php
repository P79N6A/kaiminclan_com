<?php
class wechatConnectApi
{
	private $appid;
	private $secret;
	public function __construct($config = array())
	{
		$this->appid = $config['wechat_appid'];
		$this->secret = $config['wechat_secret'];
	}
	
	public function wechat_get_token($code)
	{
		$keysarr = array(
			'appid'=>$this->appid,
			'secret'=>$this->secret,
			'code'=>$code,
			'grant_type'=>'authorization_code'
		);
		$baseurl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
		$tokenurl = $this->wechat_combine_url($baseurl,$keysarr);
		$result = $this->wechat_get_contents($tokenurl);
		$contents = json_decode($result,true);
		
		return $contents;
	}
	public function wechat_login($redirect_uri)
	{
		$wechat = array();
		$wechat['appid'] = $this->appid;
		$wechat['redirect_uri'] = $redirect_uri;
		$wechat['response_type'] = 'code';
		$wechat['scope'] = 'snsapi_login';
		$wechaturl = http_build_query($wechat);
		$url = 'https://open.weixin.qq.com/connect/qrconnect?'.$wechaturl;
		return $url;
	}
	public function wechat_auth_token($access_token, $openid)
	{
		$keysarr = array(
			'openid'=>$openid,
			'access_token'=>$access_token
		);
		
		$baseurl = 'https://api.weixin.qq.com/sns/auth';
		$authurl = $this->wechat_combine_url($baseurl,$keysarr);
		$result = $this->wechat_get_contents($authurl);
		$contents = json_decode($result,true);
		if($contents['errcode'])
		{
			return false;
		}
		return true;
	}
	
	public function wechat_get_userinfo($access_token, $openid)
	{
		$token_auth = $this->wechat_auth_token($access_token,$openid);
		if($token_auth){
			$keysarr = array(
				'openid'=>$openid,
				'access_token'=>$access_token
			);
			$baseurl = 'https://api.weixin.qq.com/sns/userinfo';
			$result = $this->wechat_get_contents($baseurl,$keysarr);
			$contents = json_decode($result,true);
			if($contents['errcode'])
			{
				throw new Exception('获取用户信息失败',$contents['errcode']);
			}
			return $contents;
		}
	}
	public function wechat_combine_url($baseurl,$keysarr){
		$combined = $baseurl."?";
		$value = array();
		
		foreach($keysarr as $key => $val){
            $value[] = "$key=$val";
        }
        $keystr = implode("&",$value);
        $combined .= $keystr;
        return $combined;
    }
	
	private function wechat_get_contents($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response =  curl_exec($ch);
		curl_close($ch);
		return $response;
    }
	
}