<?php
class PermanentIndebtednessBlock extends Block {
	public function getdata($param){
		
		$indebtednessId = isset($param['indebtednessId'])?$param['indebtednessId']:0;
		$creditId = isset($param['creditId'])?$param['creditId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($indebtednessId){
			$where['identity'] = $indebtednessId;
		}
		
		if($creditId){
			$where['credit_identity'] = $creditId;
		}
		
		$listdata = $this->service('PermanentIndebtedness')->getIndebtednessList($where,$start,$perpage,$order);
		if($perpage == 1){
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