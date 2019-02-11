<?php
/**
 *
 * 账户修改
 *
 * 20180301
 *
 */
class SubscriberChangeController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'nickname'=>array('type'=>'string','tooltip'=>'昵称'),
			'fullname'=>array('type'=>'string','tooltip'=>'姓名'),
			'mobile'=>array('type'=>'mobile','tooltip'=>'电话'),
			'sex'=>array('type'=>'digital','tooltip'=>'性别'),
			'birthday'=>array('type'=>'digital','tooltip'=>'生日'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$nickname = $this->argument('nickname');
		$fullname = $this->argument('fullname');
		
		$mobile = $this->argument('mobile');
		$sex = $this->argument('sex');
		$birthday = strtotime(implode('-',$this->argument('birthday')));
		
		$userInfo = $this->service('AuthoritySubscriber')->getSubscriberInfo($this->session('uid'));
		if(!$userInfo){
			$this->info('用户不存在',40001);
		}
		
		$userData = array(
			'fullname'=>$nickname
		);
		
		$this->service('AuthoritySubscriber')->update($userData,$this->session('uid'));
		
		//客户信息
		$clientData = array(
			'fullname'=>$fullname,
			'mobile'=>$mobile,
			'sex'=>$sex,
			'birthday'=>$birthday
		);
		
	}
}
?>