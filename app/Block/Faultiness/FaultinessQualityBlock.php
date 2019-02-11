<?php

class FaultinessQualityBlock extends Block {
	private $subjectId = 0;
	public function getdata($param){
		
		
		$bulletinId = isset($param['bulletinId'])?$param['bulletinId']:-1;
		$status = isset($param['status'])?$param['status']:-1;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):-1;
		
		$start = isset($param['start'])?intval($param['start']):-1;
		
		$frontendId = $param['frontendId'];
		$subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;
		
		$where = array();
		
		
		$where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }
		if($bulletinId != -1){
			$where['identity'] = $bulletinId;
		}
		
		if($status != -1){
			$where['status'] = $status;
		}
		if($subjectId){
			$where['subject_identity'] = $subjectId;
		}
		$listdata = $this->service('FaultinessQuality')->getQualityList($where,$start,$perpage);
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