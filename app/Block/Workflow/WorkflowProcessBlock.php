<?php
class WorkflowProcessBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$processId = isset($param['processId'])?$param['processId']:0;
		
		$where = array();
		if($processId){
			$where['identity'] = $processId;
		}
				
		$listdata = $this->service('WorkflowProcess')->getProcessList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}