<?php
/**
 *
 * 发送手机验证码
 *
 * 20180301
 *
 */
class LoginSendCodeController extends Controller {
	
	protected $permission = 'guest';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'mobile'=>array('type'=>'mobile','tooltip'=>'手机号码'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$mobile = $this->argument('mobile');
		
		
		$where = array(
			'legal_mobile'=>$mobile
		);
		
		if($this->service('MessengerMessage')->isLocked($mobile)){
			$this->info('信息已发送，请稍后',4501);
		}
		
		$subject = '用户登录';
		$template = '验证码{CODE}，30分钟内有效，请勿泄露。';
		
		$this->service('MessengerMessage')->send(ImmediateNoticeService::MESSAGE_TYPE_MMS,$mobile,$subject,$template,array(),60*30);
	}
}
?>