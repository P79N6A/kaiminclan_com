<?php

class FabricationPluginBlock extends Block {
	private $subjectId = 0;
	
	public function getdata($param){
		
		
		$applicationId = isset($param['applicationId'])?$param['applicationId']:-1;
		$platformId = isset($param['platformId'])?$param['platformId']:-1;
        $subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;
		$status = isset($param['status'])?$param['status']:-1;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):-1;
		
		$start = isset($param['start'])?intval($param['start']):1;
		
		$where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }
		if($status != -1){
			$where['status'] = $status;
		}
		if($platformId > 0){
			$where['platform_identity'] = $platformId;
		}
		
		
		
		$listdata = $this->service('FabricationPlugin')->getPluginList($where,$start,$perpage);
		if($listdata['total']){
			if($perpage == 1){
				$listdata['list'] = current($listdata['list']);
			}
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total']);
	}
}