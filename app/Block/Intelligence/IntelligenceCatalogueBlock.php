<?php
class IntelligenceCatalogueBlock extends Block {
	public function getdata($param){
		
		$catalogueId = isset($param['catalogueId'])?$param['catalogueId']:-1;
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($catalogueId != -1){
			$where['identity'] = $catalogueId;
		}
		if($parentId != -1){
			$where['catalogue_identity'] = $parentId;
		}
		$listdata = $this->service('IntelligenceCatalogue')->getCatalogueList($where,$start,$perpage,$order);
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