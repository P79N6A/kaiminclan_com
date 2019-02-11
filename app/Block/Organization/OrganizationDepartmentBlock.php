<?php
class OrganizationDepartmentBlock extends Block {
	public function getdata($param){
				
		$keyword = isset($param['kw'])?$param['kw']:0;
		$departmentId = isset($param['departmentId'])?$param['departmentId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:0;
		$companyId = isset($param['companyId'])?$param['companyId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		$mode = isset($param['mode'])?$param['mode']:0;
		
		
		$order = 'weight DESC,identity DESC';
		$where = array();
		if($departmentId){
			$where['identity'] = $departmentId;
		}else{
		
			$where['department_identity'] = $parentId;
			if($status != -1){
				$where['status'] = $status;
			}
		}
		if($mode){
			$where['company_identity'] = $this->session('company_identity');
		}
		if($companyId){
			$where['company_identity'] = $companyId;
		}
		$listdata = $this->service('OrganizationDepartment')->getDepartmentList($where,$start,$perpage,$order);
		
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