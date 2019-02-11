<?php

class FabricationJoggleBlock extends Block {
	private $subjectId = 0;
	
	public function getdata($param){
		
		$joggleId = isset($param['joggleId'])?$param['joggleId']:-1;
		$applicationId = isset($param['applicationId'])?$param['applicationId']:-1;
		$functionalId = isset($param['functionalId'])?$param['functionalId']:-1;
		$subjectId = isset($param['subjectId'])?$param['subjectId']:-1;
		$status = isset($param['status'])?$param['status']:-1;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):-1;
		
		$start = isset($param['start'])?intval($param['start']):1;
		$start = $start < 1?1:$start;
		
		
		$where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }
		if($applicationId != -1){
			$where['application_identity'] = $applicationId;
		}
		if($functionalId != -1){
			$where['functional_identity'] = $functionalId;
		}
		if($subjectId > 0){
			$where['subject_identity'] = $subjectId;
		}
		if($joggleId > 0){
			$where['identity'] = $joggleId;
		}
		
		$listdata = $this->service('FabricationJoggle')->getJoggleList($where,$start,$perpage);
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