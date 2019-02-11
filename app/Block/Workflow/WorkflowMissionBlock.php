<?php
class WorkflowMissionBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$missionId = isset($param['missionId'])?$param['missionId']:0;
        $mode = isset($param['mode'])?$param['mode']:0;
		
		$where = array();
		if($missionId){
			$where['identity'] = $missionId;
		}
		if($mode){
		    $where['subscriber_identity'] = $this->session('uid');
        }
				
		$listdata = $this->service('WorkflowMission')->getMissionList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}