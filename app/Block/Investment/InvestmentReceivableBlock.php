<?php
class InvestmentReceivableBlock extends Block {
	public function getdata($param){
		
		$receivableId = isset($param['receivableId'])?$param['receivableId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($receivableId){
			$where['identity'] = $receivableId;
		}
		$where['expire_date'] = array('between',array(strtotime(date('Ym').'01'),strtotime(date('Ym').'31')));
		
		$listdata = $this->service('InvestmentReceivable')->getReceivableList($where,$start,$perpage,$order);
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