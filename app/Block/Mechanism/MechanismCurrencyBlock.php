<?php
class MechanismCurrencyBlock extends Block {
	public function getdata($param){
		
		$currencyId = isset($param['currencyId'])?$param['currencyId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($currencyId){
			$where['identity'] = $currencyId;
			
		}
		
		$listdata = $this->service('MechanismCurrency')->getCurrencyList($where,$start,$perpage,$order);
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