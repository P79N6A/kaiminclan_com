<?php
class ProjectLeaguerBlock extends Block {
	public function getdata($param){
		
		$memberId = isset($param['memberId'])?$param['memberId']:0;
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$order = 'identity desc';
		$where = array();
		if($memberId){
			$where['identity'] = $memberId;
			
		}
        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }
		
		$listdata = $this->service('ProjectLeaguer')->getLeaguerList($where,$start,$perpage,$order);
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