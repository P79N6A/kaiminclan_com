<?php
/**
 *
 类型【1账户，2指定账户，3指定角色，4指定区域，5本角色，6本角色及以下，7本区域，8本区域及以下】
 */
class AuthorityManageService extends Service {
	
	
	public function pushBySubscriberId($subscriberId,$fashion,$range){
		$manageData = array(
			'idtype'=>AuthorityManageModel::AUTHORITY_MANAGE_AUTHORITY_TYPE_USER,
			'id'=>$subscriberId,
			'fashion'=>$fashion,
			'range'=>$range,
		);
		return $this->insert($manageData);	
	}
	
	public function pushByRoleId($roleId,$fashion,$range){
		$manageData = array(
			'idtype'=>AuthorityManageModel::AUTHORITY_MANAGE_AUTHORITY_TYPE_ROLE,
			'id'=>$roleId,
			'fashion'=>$fashion,
			'range'=>$range,
		);
		return $this->insert($manageData);	
	}
	
	public function insert($manageData){
		
		$manageData['subscriber_identity'] = $this->getUID();
		$manageData['dateline'] = $this->getTime();
		$manageData['lastupdate'] = $manageData['dateline'];
		
		return $this->model('AuthorityManage')->data($manageData)->add();
	}
	
	public function getManageList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('AuthorityManage')->where($where)->count();
		if($count){
			$selectHandle = $this->model('AuthorityManage')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 * @param $roleId  角色
	 */
	public function getManageByRoleId($roleId){
		$adminId = array();
		//获取账户资源
		$where = array(
			'idtype'=>AuthorityManageModel::AUTHORITY_MANAGE_AUTHORITY_TYPE_ROLE,
			'id'=>$roleId,
			'status'=>AuthorityManageModel::AUTHORITY_MANAGE_STATUS_ENABLE
		);
		
		$list = $this->model('AuthorityManage')->where($where)->select();
		if($list){
			foreach($list as $key=>$data){
				$block  = AuthorityManageModel::getFashionCode($data['fashion']);
				if(!$block){
					continue;
				}
				if(strpos($data['range'],',') !== false){
					$adminId[$block] = array_merge(explode(',',$data['range']),$adminId[$block]);
				}else{
					$adminId[$block][] = $data['range'];
				}
			}
			foreach($adminId as $block=>$ids){
				$adminId[$block] = array_unique($ids);
			}
		}
		return $adminId;
	}
	/**
	 * @param $subscriberId 账户
	 */
	public function getManageBySubscriberId($subscriberId){
		$adminId = array();
		//获取账户资源
		$where = array(
			'idtype'=>AuthorityManageModel::AUTHORITY_MANAGE_AUTHORITY_TYPE_USER,
			'id'=>$subscriberId,
			'status'=>AuthorityManageModel::AUTHORITY_MANAGE_STATUS_ENABLE
		);
		
		$list = $this->model('AuthorityManage')->where($where)->select();
		if($list){
			foreach($list as $key=>$data){
				$block  = AuthorityManageModel::getFashionCode($data['fashion']);
				if(!$block){
					continue;
				}
				if(strpos($data['range'],',') !== false){
					$adminId[$block] = array_merge(explode(',',$data['range']),$adminId[$block]);
				}else{
					$adminId[$block][] = $data['range'];
				}
			}
			foreach($adminId as $block=>$ids){
				$adminId[$block] = array_unique($ids);
			}
		}
		return $adminId;
		
	}
	
	public function getManageByAccount($roleId,$uid){
		return array_merge($this->getManageByRoleId($roleId),$this->getManageBySubscriberId($uid));
	}
}