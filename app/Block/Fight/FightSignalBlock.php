<?php
class FightSignalBlock extends Block {
	public function getdata($param){
		
		$policyId = isset($param['policyId'])?$param['policyId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($policyId){
			$where['identity'] = $policyId;
		}
		
		$listdata = $this->service('FightSignal')->getSignalList($where,$start,$perpage,$order);
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