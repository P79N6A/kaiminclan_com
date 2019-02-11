<?php
/***
 *
 * 账户模块
 *
 */
class AuthoritySubscriberBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		
		$roleId = isset($param['roleId'])?$param['roleId']:0;
		$keyword = isset($param['kw'])?$param['kw']:0;
		$subscriberId = isset($param['subscriberId'])?$param['subscriberId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		
		$where = array();
		
		if($roleId){
			$where['role_identity'] = $roleId;
		}
		if($subscriberId){
			$where['identity'] = $subscriberId;
		}
		if($mode){
			$where['business_identity'] = $this->session('business_identity');
		}
		
		if($keyword){
			$where['username'] = array('like','%'.$keyword.'%');
		}
		
		$count = $this->model('AuthoritySubscriber')->where($where)->count();
		
		$listdata = array();
		if($count){
			$subscriberHandle  = $this->model('AuthoritySubscriber')->where($where);
			
			if($perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			
			$roleIds = array();
			foreach($listdata as $key=>$data){
				$roleIds[] = $data['role_identity'];
			}
			
			$roleData = $this->service('AuthorityRole')->getRoleInfo($roleIds);
			
			foreach($listdata as $key=>$data){
				$role = array(
					'identity'=>$data['role_identity'],
					'title'=>''
				);
				if(isset($roleData[$data['role_identity']])){
					$role = $roleData[$data['role_identity']];
				}
				$listdata[$key]['role'] = $role;
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>AuthoritySubscriberModel::getStatusTitle($data['status'])
				);
			}
			if($perpage == 1){
				$listdata = current($listdata);
			}
		}
		return array('data'=>$listdata,'total'=>$count,'perpage'=>$perpage,'start'=>$start);
	}
}