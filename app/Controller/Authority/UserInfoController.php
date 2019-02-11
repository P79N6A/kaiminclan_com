<?php
/**
 *
 * 账户信息
 *
 * 20180301
 *
 */
class UserInfoController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'get';	
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$this->assign('memdata',current($this->service('AuthoritySubscriber')->getSubscriberInfo($this->getUID())));		
	}
}
?>