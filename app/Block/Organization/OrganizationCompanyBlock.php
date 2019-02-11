<?php
class OrganizationCompanyBlock extends Block {
	public function getdata($param){
				
		$keyword = isset($param['kw'])?$param['kw']:0;
		$companyId = isset($param['companyId'])?$param['companyId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		$mode = isset($param['mode'])?$param['mode']:0;
		
		$order = 'identity desc';
		
		$where = array();
		if($companyId){
			$where['identity'] = $companyId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($parentId != -1){
			if($mode && $parentId < 1){
				$parentId = $this->session('company_identity');
			}
			$where['company_identity'] = $parentId;
		}
		
		
		$listdata = $this->service('OrganizationCompany')->getCompanyList($where,$start,$perpage,$order);
		

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