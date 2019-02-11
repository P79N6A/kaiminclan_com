<?php
class InvestmentCatalogBlock extends Block {
	public function getdata($param){
		
		$catalogId = isset($param['catalogId'])?$param['catalogId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($catalogId){
			$where['identity'] = $catalogId;
		}
		
		if($parentId != -1){
			$where['industry_identity'] = $parentId;
		}
		
		$listdata = $this->service('InvestmentCatalog')->getCatalogList($where,$start,$perpage,$order);
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