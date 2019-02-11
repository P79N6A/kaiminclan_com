<?php
class ProjectSubjectBlock extends Block {
	public function getdata($param){
		
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?intval($param['perpage']):10;
        $mode = isset($param['mode'])?$param['mode']:10;
		$order = 'identity desc';
		$where = array();
		if($subjectId){
			$where['identity'] = $subjectId;
		}
		if($mode){
            $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
			if($subjectId){
				if(in_array($subjectId,$allowedSubjectIds)){
					$where['identity'] = $subjectId;
				}else{
					$where['identity'] = 0;
				}
			}else{
				$where['identity'] = $allowedSubjectIds;
			}
        }

		
		$listdata = $this->service('ProjectSubject')->getSubjectList($where,$start,$perpage,$order);
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