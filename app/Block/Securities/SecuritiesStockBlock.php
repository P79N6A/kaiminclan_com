<?php
class SecuritiesStockBlock extends Block {
	public function getdata($param){
		
		$stockId = isset($param['stockId'])?$param['stockId']:0;
		$industryId = isset($param['industryId'])?$param['industryId']:-1;
		$start = isset($param['start'])?$param['start']:1;
		$perpage = isset($param['perpage'])?$param['perpage']:10;;
		$kw = isset($param['kw'])?$param['kw']:'';
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($stockId){
			$where['identity'] = $stockId;
		}
		if($industryId != -1){
			$where['industry_identity'] = $industryId;
		}
		if($kw){
			$where['title'] = array('like','%'.$kw.'%');
		}
		$listdata = $this->service('SecuritiesStock')->getStockList($where,$start,$perpage,$order);
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