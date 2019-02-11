<?php
class OrganizationProspectusBlock extends Block {
	public function getdata($param){
				
		$keyword = isset($param['kw'])?$param['kw']:0;
		$prospectusId = isset($param['prospectusId'])?$param['prospectusId']:0;
		$companyId = isset($param['companyId'])?$param['companyId']:0;
		$departmentId = isset($param['departmentId'])?$param['departmentId']:0;
		$employeeId = isset($param['employeeId'])?$param['employeeId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?$param['mode']:0;
		
		
		$order = 'identity desc';
		$where = array();
		if($prospectusId){
			$where['identity'] = $prospectusId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($mode){
			$where['company_identity'] = $this->session('company_identity');
		}
		if($companyId){
			$where['company_identity'] = $companyId;
		}
		if($departmentId){
			$where['department_identity'] = $departmentId;
		}
		if($employeeId){
			$where['employee_identity'] = $employeeId;
		}
		
		$listdata = $this->service('OrganizationProspectus')->getProspectusList($where,$start,$perpage,$order);
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