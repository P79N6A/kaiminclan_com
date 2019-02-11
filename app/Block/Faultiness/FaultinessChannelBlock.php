<?php

class FaultinessChannelBlock extends Block {
	private $subjectId = 0;
	public function getdata($param){
		
		
		$bulletinId = isset($param['bulletinId'])?$param['bulletinId']:-1;
		$status = isset($param['status'])?$param['status']:0;
		
		$perpage = isset($param['perpage'])?intval($param['perpage']):0;
		
		$start = isset($param['start'])?intval($param['start']):0;
		
		$frontendId = $param['frontendId'];
		$subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;
		
		$where = array();
		
		
		$where = array();
		if($bulletinId != -1){
			$where['identity'] = $bulletinId;
		}
		
		if($status != -1){
			$where['status'] = $status;
		}
		if($subjectId){
			$where['subject_identity'] = $subjectId;
		}
		
				
		$listdata = $this->service('FaultinessChannel')->getChannelList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}