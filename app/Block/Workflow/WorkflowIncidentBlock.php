<?php
class WorkflowIncidentBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$indicentId = isset($param['indicentId'])?$param['indicentId']:0;
		
		$where = array();
		if($indicentId){
			$where['identity'] = $indicentId;
		}
				
		$listdata = $this->service('WorkflowIncident')->getIncidentList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}