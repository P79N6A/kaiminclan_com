<?php
/**
 *
 * 微信登录
 *
 * 权限
 *
 */
class LoginWechatController extends Controller {
	
	protected $permission = 'guest';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'code'=>array('type'=>'string','tooltip'=>'','default'=>''),
			'userType'=>array('type'=>'digital','tooltip'=>'用户类型','default'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_CLIENT)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$code = $this->argument('code');
		$userType = $this->argument('userType');
		if($code){
			$connectData = $this->service('AuthorityConnect')->getOpenid($code);
			
			$openid = $connectData['openid'];
			
			$where = array();
			$where['platform'] = AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_PLATFORM_WEIXIN;
			$where['openid']= $openid;
			$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
			if(!$subscriberData){
				
				
				$subscriberData = array(
					'platform'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_PLATFORM_WEIXIN,
					'openid'=>$openid,
					'attachment_identity'=>$connectData['headimgurl'],
					'fullname'=>$connectData['nickname'],
					'role_identity'=>AuthorityRoleModel::AUTHORITY_ROLE_USER,
					'logindate'=>$this->getTime()
				);
				
				switch($userType){
					case AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_CLIENT:
						$clientData = $this->service('Customer')->customerAdd(array('sex'=>0));
						$subscriberData['id'] = $clientData['data'];
						$subscriberData['idtype'] = $userType;
						
					break;
				}
				
				$subscriberData['identity'] = $this->service('AuthoritySubscriber')->insert($subscriberData);
			}else{
				$where = array();
				$where['identity'] = $subscriberData['identity'];
				$this->model('AuthoritySubscriber')->data(array('logindate'=>$this->getTime()))->where($where)->save();
			}
			$this->session()->destroy();
			$this->session('uid',$subscriberData['identity']);
			$this->session('openid',$subscriberData['openid']);
			$this->session('roleType',$subscriberData['role_identity']);
			$this->session('roleId',$subscriberData['role_identity']);
			header('Location:'.' http://www.shouzhangyushe.com/');
			exit();
		}
		
		$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/userType/'.$userType);
		
		$url = $this->service('AuthorityConnect')->getLoginUrl($baseUrl);
		header('Location:'.$url);
	}
}
?>