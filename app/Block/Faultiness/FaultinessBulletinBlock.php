<?php

class FaultinessBulletinBlock extends Block {
	private $subjectId = 0;
	public function getdata($param){
		
		
		$bulletinId = isset($param['bulletinId'])?$param['bulletinId']:-1;
		$status = isset($param['status'])?$param['status']:0;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):0;
		
		$start = isset($param['start'])?intval($param['start']):0;
		
		$frontendId = isset($param['frontendId'])?intval($param['frontendId']):-1;
		$subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;
		$mode = isset($param['mode'])?intval($param['mode']):0;

		
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
		
				
		$listdata = $this->service('FaultinessBulletin')->getBulletinList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}