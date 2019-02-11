<?php
/**
 *
 * 账户锁定
 *
 * 20180301
 *
 */
class SubscriberResetPasswdController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subscriberId'=>array('type'=>'digital','tooltip'=>'账户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subscriberId = $this->argument('subscriberId');
		
		$subscriberInfo = $this->service('AuthoritySubscriber')->getSubscriberInfo($subscriberId);
		if(!$subscriberInfo){
			$this->info('账户不存在',4101);
		}
		
		$this->service('AuthoritySubscriber')->update(array(
			'password'=>123456
		),$subscriberId);
		
		
	}
}
?>