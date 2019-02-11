<?php
class OrganizationPositionBlock extends Block {
	public function getdata($param){
		
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		$positionId = isset($param['positionId'])?$param['positionId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:-1;
		$mode = isset($param['mode'])?$param['mode']:0;
		
		
		$order = 'identity desc';
		$where = array();
		if($positionId){
			$where['identity'] = $positionId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($mode){
			$where['company_identity'] = $this->session('company_identity');
		}
		
		$listdata = $this->service('OrganizationPosition')->getPositionList($where,$start,$perpage,$order);
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