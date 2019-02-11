<?php
/**
 *
 * 账户检测
 *
 * 20180301
 *
 */
class SubscriberCheckUsernameController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'username'=>array('type'=>'string','tooltip'=>'账户名称'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$username = $this->argument('username');
		
		$this->assign('total',$this->service('AuthoritySubscriber')->checkUser($username));
	}
}
?>