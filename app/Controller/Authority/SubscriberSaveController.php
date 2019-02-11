<?php
/**
 *
 * 账户编辑
 *
 * 20180301
 *
 */
class SubscriberSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subscriberId'=>array('type'=>'digital','tooltip'=>'账户ID','default'=>0),
			'fullname'=>array('type'=>'string','tooltip'=>'昵称/姓名','default'=>''),
			'mobile'=>array('type'=>'mobile','tooltip'=>'手机号码','default'=>0),
			'username'=>array('type'=>'string','tooltip'=>'账户名称'),
			'password'=>array('type'=>'password','tooltip'=>'登录密码','default'=>''),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'头像','default'=>0),
			'first_role_identity'=>array('type'=>'digital','tooltip'=>'隶属角色','default'=>0),
			'second_role_identity'=>array('type'=>'digital','tooltip'=>'隶属角色','default'=>0),
			'third_role_identity'=>array('type'=>'digital','tooltip'=>'隶属角色','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'账户状态','default'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subscriberId = $this->argument('subscriberId');
					
		$subscriberData = array(
			'fullname' => $this->argument('fullname'),
			'mobile' => $this->argument('mobile'),
			'username' => $this->argument('username'),
			'password' => $this->argument('password'),
			'first_role_identity' => $this->argument('first_role_identity'),
			'second_role_identity' => $this->argument('second_role_identity'),
			'third_role_identity' => $this->argument('third_role_identity'),
			'status' => $this->argument('status')
		);
		
		if(!$subscriberId && strlen($subscriberData['password']) < 1){
			$this->info('请提供登录密码',2003);
		}
		
		
		
		if($subscriberId){
			$this->service('AuthoritySubscriber')->update($subscriberData,$subscriberId);
		}else{
			
			if($this->service('AuthoritySubscriber')->checkUser($subscriberData['username'])){
				
				$this->info('账户已存在',4001);
			}
			
			if($this->service('AuthoritySubscriber')->checkUser($subscriberData['mobile'])){
				
				$this->info('联系电话已存在',4002);
			}
			
			$this->service('AuthoritySubscriber')->insert($subscriberData);
		}
	}
}
?>