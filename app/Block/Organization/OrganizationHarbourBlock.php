<?php
class OrganizationHarbourBlock extends Block {
	public function getdata($param){
				
		$keyword = isset($param['kw'])?$param['kw']:0;
		$harbourId = isset($param['harbourId'])?$param['harbourId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?$param['mode']:0;
		
		
		$order = 'identity desc';
		$where = array();
		if($harbourId){
			$where['identity'] = $harbourId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		if($mode){
			$where['company_identity'] = $this->session('company_identity');
		}
		
		$listdata = $this->service('OrganizationHarbour')->getHarbourList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}