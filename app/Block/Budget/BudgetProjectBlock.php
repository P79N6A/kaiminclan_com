<?php
class BudgetProjectBlock extends Block {
	public function getdata($param){
		
		$projectId = isset($param['projectId'])?$param['projectId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$order = 'identity desc';
		$where = array();
		
		if($projectId){
			$where['identity'] = $projectId;
		}
		$listdata = $this->service('BudgetProject')->getProjectList($where,$start,$perpage,$order);
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