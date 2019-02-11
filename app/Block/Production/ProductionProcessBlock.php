<?php

class ProductionProcessBlock extends Block {
	private $subjectId = 0;
	
	public function getdata($param){
		
		$processId = isset($param['processId'])?$param['processId']:0;
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$status = isset($param['status'])?$param['status']:-1;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):0;
		
		$start = isset($param['start'])?intval($param['start']):0;
		
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
		if($processId){
			$where['identity'] = $processId;
		}
		
		$listdata = $this->service('ProductionProcess')->getProcessList($where,$start,$perpage);
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