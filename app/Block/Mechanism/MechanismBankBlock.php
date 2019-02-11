<?php
class MechanismBankBlock extends Block {
	public function getdata($param){
		
		$bankcardId = isset($param['bankId'])?$param['bankId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($bankcardId){
			$where['identity'] = $bankcardId;
			
		}
		
		$listdata = $this->service('MechanismBank')->getBankList($where,$start,$perpage,$order);
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