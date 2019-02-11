<?php
/**
 *
 * 删除账户
 *
 * 20180301
 *
 */
class SubscriberDeleteController extends Controller {
	
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
		
		
		$this->service('AuthoritySubscriber')->removeSubscriberId($removeSubscriberIds);
		
	}
}
?>