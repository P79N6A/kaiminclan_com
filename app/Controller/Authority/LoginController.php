<?php
/**
 *
 * 登录
 *
 * 20180301
 *
 */
class LoginController extends Controller {
	
	protected $permission = 'guest';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'username'=>array('type'=>'username','tooltip'=>'账户名称'),
			'password'=>array('type'=>'password','tooltip'=>'登录密码'),
            'returnUrl'=>array('type'=>'string','tooltip'=>'登录密码','default'=>''),
			'lifetime'=>array('type'=>'digital','tooltip'=>'有效期','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$username = $this->argument('username');
		$password = $this->argument('password');
		$lifetime = $this->argument('lifetime');
        $fromUrl = $this->argument('returnUrl');
		
		$where = array();
		if(strpos($username,'@')){
			$where['email'] = $username;
		}
		elseif(is_numeric($username) && strcmp(strlen($username),11) === 0){
			$where['mobile'] = $username;
		}else{
			$where['username'] = $username;
			
		}
			
		$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
		if(!$subscriberData){
			$this->service('SecurityAbnormal')->push('账户不存在');
			$this->info('账户不存在',5100);
		}
		
		if($subscriberData['status'] != AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_ENABLE){
			$this->service('SecurityAbnormal')->push('账户禁用');
			$this->info('账户禁用',5101);
		}
		
		if($subscriberData['password'] != $this->service('AuthoritySubscriber')->encryptionPassword($password,$subscriberData['salt'])){			
			$this->service('SecurityAbnormal')->push('密码错误');
			$this->info('密码错误',5102);
		}
		$roleType = $this->service('AuthorityRole')->getRoleType($subscriberData['second_role_identity']);
		if(!$roleType){
			$this->info('登录失败',5103);
		}
		$this->session()->destroy();
		
        $returnUrl = '/dashboard.html';
		switch($roleType){
			case AuthorityRoleModel::AUTHORITY_ROLE_SUPPLIER:
				$this->session('business_identity',$subscriberData['id']);
				break;
			case AuthorityRoleModel::AUTHORITY_ROLE_ADMIN:
				$employeeData = $this->service('OrganizationEmployee')->getEmployeeBaseInfo($subscriberData['id']);
				$this->session('employee_identity',$subscriberData['id']);
				$this->session('company_identity',$employeeData['company_identity']);
				break;
			case AuthorityRoleModel::AUTHORITY_ROLE_USER:
				$clienteteData = $this->service('CustomerClientete')->getClienteteInfo($subscriberData['id']);
				$this->session('customer_identity',$clienteteData['identity']);
				$this->session('customer',$clienteteData);
				$returnUrl = '/home.html';
				break;
		}
		
        if($fromUrl){
            $returnUrl = $fromUrl;
        }
		
		$this->session('uid',$subscriberData['identity']);
		$this->session('roleType',$roleType);
		$this->session('roleId',$subscriberData['second_role_identity']);
		$this->session('allowAction',$this->service('AuthorityResources')->getResourcesListByUser($subscriberData['role_identity'],$subscriberData['identity']));
		$this->session('manage',$this->service('AuthorityManage')->getManageByAccount($subscriberData['role_identity'],$subscriberData['identity']));

		
		$where = array();
		$where['identity'] = $subscriberData['identity'];
		$this->model('AuthoritySubscriber')->data(array('logindate'=>$this->getTime()))->where($where)->save();
		
		$this->assign('accessToken',$this->session('accessToken'));
        $this->assign('returnUrl',$returnUrl);
		
	}
}
?>