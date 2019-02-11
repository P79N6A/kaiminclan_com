<?php
class WorkflowProcedureBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$procedureId = isset($param['procedureId'])?$param['procedureId']:0;
		
		$where = array();
		if($inventoryId){
			$where['identity'] = $inventoryId;
		}
				
		$listdata = $this->service('WorkflowProcedure')->getProcedureList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}