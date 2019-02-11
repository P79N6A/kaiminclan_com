<?php
class PermanentPayableBlock extends Block {
	public function getdata($param){
		
		$payableId = isset($param['payableId'])?$param['payableId']:0;
		$indebtednessId = isset($param['indebtednessId'])?$param['indebtednessId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($payableId){
			$where['identity'] = $payableId;
		}
		if($indebtednessId){
			$where['id'] = $indebtednessId;
			$where['idtype'] = PermanentPayableModel::PERMANENT_PAYABLE_IDTYPE_INDEBTEDNESS;
		}else{
			$where['expire_date'] = array('between',array(strtotime(date('Ym').'01'),strtotime(date('Ym').'31')));
		}
		$listdata = $this->service('PermanentPayable')->getPayableList($where,$start,$perpage,$order);
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