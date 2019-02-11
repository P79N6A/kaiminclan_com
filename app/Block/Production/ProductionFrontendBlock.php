<?php

class ProductionFrontendBlock extends Block {
	private $subjectId = 0;
	public function getdata($param){
		
		$frontendId = isset($param['frontendId'])?$param['frontendId']:0;
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$catalogueId = isset($param['catalogueId'])?$param['catalogueId']:0;
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$status = isset($param['status'])?$param['status']:-1;
		$liabilityId = isset($param['liabilityId'])?intval($param['liabilityId']):-1;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):0;
		
		$start = isset($param['start'])?intval($param['start']):0;
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
		if($subjectId){
			if(in_array($subjectId,$allowedSubjectIds)){
				$allowedSubjectIds = $subjectId;
			}else{
				$allowedSubjectIds = array();
			}
		}
        $where['subject_identity'] = $allowedSubjectIds;
		if($frontendId){
			$where['identity'] = $frontendId;
		}
		
		if($platformId > 0){
			$where['platform_identity'] = $platformId;
		}
		if($catalogueId > 0){
			$where['catalogue_identity'] = $catalogueId;
		}
		
		
		switch($mode){
			case 1:
				$roleId = $this->session('roleId');
				if(!in_array($roleId,array(5,11))){
					if($roleId == 11){
						$where['status'] = array(0,3,4,5,6,7,8);
					}
					$uid = $this->session('uid');
					$where['or'] = array('subscriber_identity'=>$uid,'liability_subscriber_identity'=>$uid);
				}
				break;
			case 2:
				$where['subscriber_identity'] = $this->session('uid');
				break;
			case 3:
				$where['liability_subscriber_identity'] = $this->session('uid');
				break;
		}
		
		if($status != -1 && !isset($where['status'])){
			$where['status'] = $status;
		}
		
		if($liabilityId > 0){
			$where['liability_subscriber_identity'] = $liabilityId;
		}
		
		$listdata = $this->service('ProductionFrontend')->getFrontendList($where,$start,$perpage);
		if($listdata['total']){
			if($perpage  == 1){
				$listdata['list'] = current($listdata['list']);
			}
		}
		
		return array(
			'data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start
		);
	}
}