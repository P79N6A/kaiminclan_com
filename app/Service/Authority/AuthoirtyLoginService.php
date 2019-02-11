<?php
/***
授权与认证

//IP，设备，用户
//组合
//IP+用户名
//设备+用户名
//IP+设备+用户名
//对象
//单IP最大错误次数
//单用户最大错误次数
//设备+IP+用户最大错误次数

//惩罚机制
//错误3次，验证码
//错误5次，锁定15分钟
//错误10次，锁定24小时
//错误15次，账户彻底锁定，需要走账户解锁流程

*/
class AuthoirtyLoginService extends Service {
	private $config = array();
	public function init(){
		$this->config = $this->registry->getAccess();
	}
	public function login($username,$password,$roleId){
		
		$where = array();
		if(strpos($username,'@')){
			$where['email'] = $username;
		}
		elseif(is_numeric($username) && strcmp(strlen($username),11) === 0){
			$where['mobile'] = $username;
		}else{
			$where['username'] = $username;hh
			
		}
		$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
		if(!$subscriberData){
			$this->service('SecurityAbnormal')->pushLogin('账户碰撞');
			return -1;
		}
		
		if($subscriberData['status'] != AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_ENABLE){
			return -2;
		}
		
		if($subscriberData['password'] != $this->service('AuthoritySubscriber')->encryptionPassword($password,$subscriberData['salt'])){
			$this->service('SecurityAbnormal')->pushLogin('密码爆破');
			return -3;
		}
		$roleType = $this->service('AuthorityRole')->getRoleType($subscriberData['role_identity']);
		if(!$roleType){
			$this->service('SecurityAbnormal')->pushLogin('身份碰撞');
			return -4;
		}
		
		$degreeList = $this->service('AuthorityDegree')->fetchDegreeDataBySubscriberIds($subscriberData['identity']);
		if($degreeList){
			foreach($degreeList as $uid=>$degree){
				
			}
		}
		$this->session()->degerty();
	}
	/**
	 * 权限
	 */
	public function permission(){
		
	}
	
}