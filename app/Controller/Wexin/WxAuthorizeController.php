<?php
/**
 * 授权
 *
 * 微信管理
 */
class WxAuthorizeController extends Controller {
	protected $permission = 'realtime';
	
    protected $method = 'get';
	
    protected function setting(){
        return array(
			'code'=>array('type'=>'string','tooltip'=>'','default'=>'')
        );
	}

	public function fire(){
		$code = $this->argument('code');
		$url = '';
		if($code){
			$connectData = $this->service('AuthorityConnect')->getOpenid($code);
			
			$openid = $connectData['openid'];
			$relationData = $this->service('AuthorityRelation')->getRelationByWeixinOpenId($openid);
			if(!$relationData){
				$relationId = $this->service('AuthorityRelation')->newWeixin($openid,$connectData['nickname'],$connectData['headimgurl']);
			}else{
				if($relationData['fullname'] != $connectData['nickname'] || $relationData['attachment_identity'] != $connectData['headimgurl']){
					$this->service('AuthorityRelation')->update(array('fullname'=>$connectData['nickname'],'attachment_identity'=>$connectData['headimgurl']),$relationData['identity']);
				}
				$relationId = $relationData['identity'];
			}
			$this->session()->destroy($openid);
			$this->session('openid',$openid);
			$this->session('relationId',$relationId);
			$refererUrl = $this->cookie('openIdRefererUrl');
			if(!$refererUrl){
				$refererUrl = 'http://'.(IN_MOBILE == 1?'m':__HOST__).'.'.__SITE_URL__;
			}else{
				$refererUrl = base64_decode($refererUrl);
			}
			$this->cookie('openIdRefererUrl','');
			header('LOCATION:'.$refererUrl);
		}
        $base_url = '';
		$openId = $this->session('openid');
		if(strcmp(strlen($openId),28) !== 0){
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);		
			$url = $this->service('AuthorityConnect')->getLoginUrl($baseUrl);
		}
			
		$this->cookie('openIdRefererUrl',base64_encode($_SERVER['HTTP_REFERER']));
		$this->assign('redirectUrl',$url);
		$this->assign('base_url',$base_url);
	}
}