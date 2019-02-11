<?php
/**
 *
 * 账户解除锁定
 *
 * 20180301
 *
 */
class SubscriberUnlockController extends Controller {
	
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
		
		$groupInfo = $this->service('AuthoritySubscriber')->getSubscriberInfo($subscriberId);
		if(!$groupInfo){
			$this->info('账户不存在',4101);
		}
		
		if($groupInfo['status'] == AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_DISABLED){
			$this->service('AuthoritySubscriber')->update(array('status'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_ENABLE),$subscriberId);
		}
		
		
	}
}
?>