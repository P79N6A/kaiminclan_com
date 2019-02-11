<?php

class ProductionDemandBlock extends Block {
	
	public function getdata($param){
		
		$demandId = isset($param['demandId'])?$param['demandId']:0;
		$frontendId = isset($param['frontendId'])?$param['frontendId']:0;
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$liabilityId = isset($param['liabilityId'])?$param['liabilityId']:0;
		$status = isset($param['status'])?$param['status']:-1;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):1;
		$perpage = $perpage < 1?1:$perpage;
		
		$start = isset($param['start'])?intval($param['start']):1;
		$start = $start < 1?1:$start;
		
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
		if($demandId != -1){
			$where['identity'] = $demandId;
		}
		
		if($frontendId != -1){
			$where['identity'] = $this->service('ProductionDemandFrontend')->fetchDemandIdFrontendIds($frontendId);
		}
		
		
		if($platformId){
			$where['platform_identity'] = $platformId;
		}
		$roleId = $this->session('roleId');
		if($liabilityId){
			$where['liability_subscriber_identity'] = $liabilityId;
		}else{
			switch($mode){
				case 1:
					if(!in_array($roleId,array(5,11))){
						if($roleId == 11){
							$where['status'] = array(0,2,3,4,5,6,7,8);
						}
						$uid = $this->session('uid');
						$where['or'] = array('subscriber_identity'=>$uid,'liability_subscriber_identity'=>$uid);
					}else{
						
						$where['status'] = array(0,2,3,4,5,6,7,8);
					}
					break;
				case 2:
					$where['subscriber_identity'] = $this->session('uid');
					break;
				case 3:
					$where['liability_subscriber_identity'] = $this->session('uid');
					break;
				case 4:
					switch($roleId){
						case 5:
						case 9:
						case 10:
							$where['status'] = array(3,4);
						break;
						case 11:
							$where['status'] = array(5,6);
						break;
					}
					$where['liability_subscriber_identity'] = $this->session('uid');
					break;
			}
		}
		
		if($status != -1){
			$where['status'] = $status;
		}
		
		
		
		
		
		$listdata = $this->service('ProductionDemand')->getDemandList($where,$start,$perpage);
		if($listdata['total']){
			if($perpage < 2){
				$listdata['list'] = current($listdata['list']);
			}
		}
		
		return array(
			'data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start
		);
	}
}