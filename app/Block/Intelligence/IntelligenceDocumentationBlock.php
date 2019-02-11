<?php
class IntelligenceDocumentationBlock extends Block {
	public function getdata($param){
		
		$documentationId = isset($param['documentationId'])?$param['documentationId']:0;
		$catalogueId = isset($param['catalogueId'])?$param['catalogueId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		
		$status = isset($param['status'])?intval($param['status']):0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($documentationId){
			$where['identity'] = $documentationId;
		}
		
		
		if($catalogueId){
			$where['catalogue_identity'] = $this->service('IntelligenceCatalogue')->getAllCatId($catalogueId);
		}
		
		$listdata = $this->service('IntelligenceDocumentation')->getDocumentationList($where,$start,$perpage,$order);
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