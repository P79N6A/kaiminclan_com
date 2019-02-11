<?php

class FabricationApplicationBlock extends Block {
	
	public function getdata($param){
		
		$applicationId = isset($param['applicationId'])?$param['applicationId']:-1;
		$platformId = isset($param['platformId'])?$param['platformId']:-1;
		$subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;
		$status = isset($param['status'])?$param['status']:0;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):-1;
		
		$start = isset($param['start'])?intval($param['start']):1;
		$start = $start < 1?1:$start;
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }
		if($applicationId != -1){
			$where['identity'] = $applicationId;
		}
		if($platformId > 0){
			$where['platform_identity'] = $platformId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($mode){
			$allowSubjectIds = $this->service('ProjectMember')->getAllowedSubjectIds($this->session('uid'));
			
			if($subjectId > 0){
				if(!in_array($subjectId,$allowSubjectIds)){
					$subjectId = 0;
				}
			}else{
				$subjectId = $allowSubjectIds;
			}
		}
		$where['subject_identity'] = $subjectId;
		
		$listdata = $this->service('FabricationApplication')->getApplicationList($where,$start,$perpage);
		if($listdata['total']){
			if($perpage == 1){
				$listdata['list'] = current($listdata['list']);
			}
		}
		
		return array(
			'data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start
		);
	}
}