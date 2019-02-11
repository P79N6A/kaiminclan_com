<?php
class MechanismSubjectBlock extends Block {
	public function getdata($param){
		
		$subjectId = isset($param['subjectId'])?$param['subjectId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($parentId != -1){
			$where['subject_identity'] = $parentId;
		}
		if($subjectId){
			$where['identity'] = $subjectId;
			
		}
		
		$listdata = $this->service('MechanismSubject')->getSubjectList($where,$start,$perpage,$order);
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