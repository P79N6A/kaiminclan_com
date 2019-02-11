<?php
class OrganizationQuartersBlock extends Block {
	public function getdata($param){
				
		$keyword = isset($param['kw'])?$param['kw']:0;
		$quartersId = isset($param['quartersId'])?$param['quartersId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?$param['mode']:0;
		
		
		$order = 'identity desc';
		$where = array();
		if($quartersId){
			$where['identity'] = $quartersId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		if($mode){
			$where['company_identity'] = $this->session('company_identity');
		}
		
		$listdata = $this->service('OrganizationQuarters')->getQuartersList($where,$start,$perpage,$order);
		
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