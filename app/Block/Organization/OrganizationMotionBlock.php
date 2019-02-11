<?php
class OrganizationMotionBlock extends Block {
	public function getdata($param){
				
		$keyword = isset($param['kw'])?$param['kw']:0;
		$motionId = isset($param['motionId'])?$param['motionId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		$mode = isset($param['mode'])?$param['mode']:0;
		
		$order = 'identity desc';
		
		$where = array();
		if($motionId){
			$where['identity'] = $motionId;
		}
		if($status != -1){
			$where['status'] = $status;
		}		
		
		
		$listdata = $this->service('OrganizationMotion')->getMotionList($where,$start,$perpage,$order);
		

		if($listdata['total'] && $perpage == 1){			
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