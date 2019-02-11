<?php
/**
 *
 * 账户
 *
 * 权限
 *
 */
class  AuthoritySubscriberService extends Service {
	
	
	/**
	 *
	 * 获取账户列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 账户列表;
	 */
	public function getAllSubscriberList($where = array(),$orderby = 'identity desc',$start = 0,$perpage = 0){
		
		
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		
		if($count){
		$subscriberHandle = $this->model('AuthoritySubscriber')->field('identity,id,idtype,first_role_identity,username,mobile,fullname,attachment_identity')->where($where);
		if($start &&  $perpage){
			$subscriberHandle->limit($start,$perpage,$count);
		}
		$subscriberList = $subscriberHandle->select();
		
		
		if($subscriberList){
			
			$attachIds = $roleIds = array();
			foreach($subscriberList as $key=>$subscriber){
				$roleIds[] = $subscriber['first_role_identity'];
				if(is_numeric($subscriber['attachment_identity'])){
					$attachIds[] = $subscriber['attachment_identity'];
				}else{
					$subscriberList[$key]['attach']['touch'] = $subscriber['attachment_identity'];
				}
			}
			
			$attachData = $this->service('ResourcesAttachment')->getAttachUrl($attachIds);
			
			$roleData = $this->service('AuthorityRole')->getRoleInfo($roleIds,'identity,title');
			
			$subscriberData = array();
			foreach($subscriberList as $key=>$subscriber){
				$roleId = $subscriber['first_role_identity'];
				if($roleId){
					$role = $roleData[$roleId];
				}else{
					$role = array(
						'identity'=>$roleId,
						'title'=>''
					);
				}
				unset($subscriberList[$key]['first_role_identity']);
				$subscriberList[$key]['role'] = $role;
				$attachId = $subscriber['attachment_identity'];
				if($attachId){
					$attach = $roleData[$attachId];
				}else{
					$attach = array(
						'source'=>'',
						'thumb'=>'',
						'touch'=>''
					);
				}
				unset($subscriberList[$key]['attachment_identity']);
				$subscriberList[$key]['attach'] = $attach;
				$subscriberData[$subscriber['identity']] = $subscriberList[$key];
			}
		}
		}
		return array('list'=>$subscriberData,'total'=>$count);
	}
	
	
	/**
	 *
	 * 获取商户账户信息
	 * 
	 * @param $businessIds 商户ID
	 *
	 * @return array
	 *
	 */
	public function getSubscriberByBusinessId($businessIds){
		
		$output = array();
		
		$where = array();
		$where['idtype']= AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS;
		$where['id']= $businessIds;
		
		$listdata = $this->getAllSubscriberList($where);
		
		if($listdata){
			foreach($listdata['list'] as $key=>$data){
				$output[$data['id']] = $data;
			}
		}
		
		return $output;
	}
	/**
	 *
	 * 字符串
	 * 
	 * @param $length 字符串长度
	 *
	 */
	public static function getSalt(){
		
		$result = array();
		
		$str = 'abcdefghijklmnoprstuvwxyz0123456789*/~!@#$%^&*()_+{}<>?/.,';
		
		$count = strlen($str);
		for($i=0;$i< 16 ;$i++){
			$result[] = substr($str,mt_rand(0,$count-1),1);
		}
		
		return implode('',$result);
	}
	
	/**
	 *
	 * 密码加密
	 * 
	 * @param $password 密码
	 * @param $salt 识别串
	 *
	 */
	public static function encryptionPassword($password,$salt){
		return md5(md5($salt.md5($salt.$password)).$password);
	}
	
	/**
	 *
	 * 账户信息
	 *
	 * @param $subscriberId 账户ID
	 *
	 * @reutrn array;
	 */
	public function getSubscriberInfo($subscriberId,$field = '*'){
		$subscriberData = array();
		
		if(!is_array($subscriberId)){
			$subscriberId = array($subscriberId);
		}
		
		$subscriberId = array_filter(array_map('intval',$subscriberId));
		
		if(empty($subscriberId)){
			return $subscriberData;
		}
		
		
		$where = array(
			'identity'=>$subscriberId
		);
		
		$subscriberList = $this->getAllSubscriberList($where);
		if($subscriberList['total']){
			
			
			
			if(is_array($subscriberId)){
				$subscriberData = $subscriberList['list'];
			}else{
				$subscriberData = current($subscriberList['list']);
			}
		}
		
		return $subscriberData;
	}
	
	/**
	 *
	 * 账户信息
	 *
	 * @param $subscriberId 账户ID
	 *
	 * @reutrn array;
	 */
	public function getSubscriberInfobyIds($subscriberId){
		return $this->getSubscriberInfo($subscriberId);
	}
	/**
	 *
	 * 检测账户名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkUser($username){
		if($username){
			if(strpos($username,'@') !== false){
				$where = array(
					'email'=>$username
				);
			}
			elseif(is_numeric($username) && strlen($username) == 11){
				$where = array(
					'mobile'=>$username
				);
				
			}else{
				$where = array(
					'username'=>$username
				);
			}
			return $this->model('AuthoritySubscriber')->where($where)->count();
		}
		return 0;
	}
	/**
	 *
	 * 检测OPENID是否存在
	 *
	 * @param $platform 平台
	 * @param $openid OPENID
	 *
	 * @reutrn int;
	 */
	public function checkPlatformOpenId($platform,$openid){
		if($platform && $openid){
			$where = array(
				'platform'=>$platform,
				'openid'=>$openid
			);
			return $this->model('AuthoritySubscriber')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除账户
	 *
	 * @param $subscriberId 账户ID
	 *
	 * @reutrn int;
	 */
	public function removeSubscriberId($subscriberId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subscriberId
		);
		
		$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
		if($subscriberData){
			
			$output = $this->model('AuthoritySubscriber')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 账户修改
	 *
	 * @param $subscriberId 账户ID
	 * @param $subscriberNewData 账户数据
	 *
	 * @reutrn int;
	 */
	public function update($subscriberNewData,$subscriberId){
		$where = array(
			'identity'=>$subscriberId
		);
		
		$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
		if($subscriberData){
			
			if(isset($subscriberNewData['password']) && strlen($subscriberData['password']) > 0){
				$subscriberNewData['password'] = self::encryptionPassword($subscriberNewData['password'],$subscriberData['salt']);
			}
			
			$subscriberNewData['lastupdate'] = $this->getTime();
			$this->model('AuthoritySubscriber')->data($subscriberNewData)->where($where)->save();
			
			
		
			if($subscriberNewData['role_identity'] != $subscriberData['role_identity']){
				$this->service('AuthorityRole')->adjustSubscriberNum($subscriberData['role_identity'],-1);
				$this->service('AuthorityRole')->adjustSubscriberNum($subscriberNewData['role_identity'],1);
			}
		}
	}
	
	/**
	 *
	 * 新账户
	 *
	 * @param $subscriberNewData 账户信息
	 *
	 * @reutrn int;
	 */
	public function insert($subscriberNewData){
		if(!$subscriberNewData){
			return -1;
		}
			
		if(isset($subscriberNewData['password'])){
			$subscriberNewData['salt'] = self::getSalt();
			$subscriberNewData['password'] = self::encryptionPassword($subscriberNewData['password'],$subscriberNewData['salt']);
		}
		$subscriberNewData['business_identity'] =$this->session('business_identity');
		$subscriberNewData['subscriber_identity'] =$this->session('uid');
		$subscriberNewData['dateline'] = $this->getTime();
		$subscriberNewData['lastupdate'] = $subscriberNewData['dateline'];
		
		$uid = $this->model('AuthoritySubscriber')->data($subscriberNewData)->add();
		
		if($subscriberNewData['role_identity']){
			$this->service('AuthorityRole')->adjustSubscriberNum($subscriberNewData['role_identity'],1);
		}
		
		return $uid;
	}
	
	/**
	 *
	 * 修改商户密码
	 *
	 * @param $businessId 商户ID
	 * @param $password 密码
	 *
	 * @reutrn int;
	 */
	public function changePasswdByBusinessId($businessId,$password){
		$where = array();
		$where['idtype'] = AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS;
		$where['id'] = $businessId;
		
		$subscriberData = $this->model('AuthoritySubscriber')->where($where)->find();
		if(!$subscriberData){
			return -1;
		}
		
		$where = array(
			'identity'=>$subscriberData['identity']
		);
		$setarr = array(
			'password'=>self::encryptionPassword($password,$subscriberData['salt'])
		);
		$setarr['lastupdate'] = $this->getTime();
		$this->model('AuthoritySubscriber')->data($setarr)->where($where)->save();
		
	}
	
	/**
	 *
	 * 商户锁定
	 *
	 * @param $businessId 商户DI
	 *
	 * @reutrn int;
	 */
	public function disabledSubscriber($businessId){
		$where = array(
			'id'=>$businessId,
			'idtype'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS
		);
		
		return $this->model('AuthoritySubscriber')->data(array('status'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_DISABLED))->where($where)->save();
	}
	
	/**
	 *
	 * 商户解锁
	 *
	 * @param $businessId 商户DI
	 *
	 * @reutrn int;
	 */
	public function enabledSubscriber($businessId){
		$where = array(
			'id'=>$businessId,
			'idtype'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS
		);
		
		return $this->model('AuthoritySubscriber')->data(array('status'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_STATUS_ENABLE))->where($where)->save();
	}
	
	/**
	 *
	 * 新商户帐号
	 *
	 * @param $businessId 商户DI
	 * @param $username 用户名
	 * @param $mobile 手机号
	 * @param $password 密码
	 *
	 * @reutrn int;
	 */
	public function newBusinessUser($businessId,$username,$mobile,$password){
		
		$where = array();
		$where['username'] = $username;
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		if($count){
			return -1;
		}
		$where = array();
		$where['mobile'] = $mobile;
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		if($count){
			return -2;
		}
		
		$uesrdata = array(
			'username'=>$username,
			'mobile'=>$mobile,
			'password'=>$password,
			'role_identity'=>AuthorityRoleModel::AUTHORITY_ROLE_SUPPLIER,
			'id'=>$businessId,
			'idtype'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS
		);
		return $this->insert($uesrdata);
	}
	
	/**
	 *
	 * 新员工帐号
	 *
	 * @param $employeeId 员工DI
	 * @param $username 用户名
	 * @param $mobile 手机号
	 *
	 * @reutrn int;
	 */
	public function newEmployeeUser($employeeId,$mobile,$fullname,$roleId = 3,$password = 111111){
		
		$where = array();
		$where['mobile'] = $mobile;
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		if($count){
			return -1;
		}
		
		$uesrdata = array(
			'fullname'=>$fullname,
			'mobile'=>$mobile,
			'password'=>$password,
			'first_role_identity'=>$roleId,
			'id'=>$employeeId,
			'idtype'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_EMPLOYEE
		);
		return $this->insert($uesrdata);
	}
	public function newClienteteUser($clientId,$mobile,$fullname,$password = 111111){
		$where = array();
		$where['mobile'] = $mobile;
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		if($count){
			return -1;
		}
		
		$uesrdata = array(
			'fullname'=>$fullname,
			'mobile'=>$mobile,
			'password'=>$password,
			'first_role_identity'=>4,
			'second_role_identity'=>4,
			'third_role_identity'=>4,
			'id'=>$clientId,
			'idtype'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_CLIENT
		);
		return $this->insert($uesrdata);
	}
	
	public function newTradeUser($accountId,$username,$fullname,$password = 111111){
		$where = array();
		$where['username'] = $username;
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		if($count){
			return -1;
		}
		
		
		$uesrdata = array(
			'fullname'=>$fullname,
			'username'=>$username,
			'password'=>$password,
			'first_role_identity'=>27,
			'second_role_identity'=>27,
			'third_role_identity'=>27,
			'id'=>$accountId,
			'idtype'=>AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_TRADE_ACCOUNT
		);
		return $this->insert($uesrdata);
	}
}