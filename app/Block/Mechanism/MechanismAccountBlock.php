<?php
class MechanismAccountBlock extends Block {
	public function getdata($param){
		
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		$accountId = isset($param['accountId'])?$param['accountId']:0;
		$typologicalId = isset($param['typologicalId'])?$param['typologicalId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$mode = isset($param['mode'])?$param['mode']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($accountId){
			$where['identity'] = $accountId;
			
		}
		if($typologicalId){
			$where['typological_identity'] = $typologicalId;
			
		}
		if($parentId != -1){
			$where['account_identitty'] = $accountId;
		}
		
		switch($mode){
			case 1:
			$where['typological_identity'] = array(1,2,3);
			break;
			case 2:
			$where['typological_identity'] = array(4);
			break;
			case 3:
			$where['typological_identity'] = array(1);
			break;
		}
		
		if($mode){
			$where['company_identity'] = $this->session('company_identity');
		}
		
		
		$listdata = $this->service('MechanismAccount')->getAccountList($where,$start,$perpage,$order);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}