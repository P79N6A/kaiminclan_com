<?php
class ProductionCatalogueBlock extends Block {
	public function getdata($param){

        $catalogueId = isset($param['catalogueId'])?$param['catalogueId']:0;
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:0;
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}

		$allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
		if($subjectId){
			if(in_array($subjectId,$allowedSubjectIds)){
				$allowedSubjectIds = $subjectId;
			}else{
				$allowedSubjectIds = array();
			}
		}
		$where['subject_identity'] = $allowedSubjectIds;

		if($catalogueId){
			$where['identity'] = $catalogueId;
			
		}
		if($platformId){
			$where['platform_identity'] = $platformId;
		}
		if($parentId){
			$where['catalogue_identity'] = $parentId;
		}
		
		$listdata = $this->service('ProductionCatalogue')->getCatalogueList($where,$start,$perpage,$order);
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