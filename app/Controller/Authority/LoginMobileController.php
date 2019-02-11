<?php
/**
 *
 * 手机验证码登录
 *
 * 20180301
 *
 */
class LoginMobileController extends Controller {
	
	protected $permission = 'guest';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'mobile'=>array('type'=>'mobile','tooltip'=>'手机号码'),
			'code'=>array('type'=>'digital','tooltip'=>'验证码'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$mobile = $this->argument('mobile');
		$code = $this->argument('code');
		
		
		$checkCode = $this->service('MessengerMessage')->checkVerifyCode($code);
		if($checkCode){
			$this->info('验证码错误',5001);
		}
		
		$where = array();
		$where['mobile'] = $mobile;
			
		$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
		
		if(!$subscriberData){
			if($roleId == 6){
				$this->info('账户不存在',5001);
			}
			$subscriberData = array(
				'platform'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_PLATFORM_WEIXIN,
				'openid'=>$openid,
				'attachment_identity'=>$connectData['headimgurl'],
				'fullname'=>$connectData['nickname'],
				'role_identity'=>AuthorityRoleModel::AUTHORITY_ROLE_USER,
				'logindate'=>$this->getTime()
			);
				
			$this->service('AuthoritySubscriber')->insert($subscriberData);
		}else{
			$openId = $this->session('openid');
			$subscriberData['openid'] = $openId;
            $where = array();
            $where['identity'] = $subscriberData['identity'];
            $this->model('AuthoritySubscriber')->data(array('logindate' => $this->getTime(),'openid'=>$openId))->where($where)->save();
			
		}
		
		$this->session()->destroy($openId);
		$this->session('uid',$subscriberData['identity']);
		$this->session('openid',$subscriberData['openid']);
		$this->session('roleType',$subscriberData['role_identity']);
		$this->session('roleId',$subscriberData['role_identity']);
		
		$this->assign('accessToken',$this->session('accessToken'));
		
	}
}
?>