<?php
/**
 *
 * 布局
 *
 *
 */
class OrganizationEmployeeService extends Service
{
	
	/**
	 *
	 * 布局信息
	 *
	 * @param $field 布局字段
	 * @param $status 布局状态
	 *
	 * @reutrn array;
	 */
	public function getEmployeeList($where,$start,$peremployee,$order = ''){
		
		$count = $this->model('OrganizationEmployee')->where($where)->count();
		if($count){
			$handle = $this->model('OrganizationEmployee')->where($where)->orderby($order);
			if($start && $peremployee){
				$handle->limit($start,$peremployee,$count);
			}
			$listdata = $handle->select();
			$companyIds = $departmentIds = $quartersIds = $positionIds = array();
			foreach($listdata as $key=>$data){
				$companyIds[] = $data['company_identity'];		
				$departmentIds[] = $data['department_identity'];	
				$quartersIds[] = $data['quarters_identity'];	
				$positionIds[] = $data['position_identity'];			
			}
			
			$companyData = $this->service('OrganizationCompany')->getCompanyData($companyIds);
			$departmentData = $this->service('OrganizationDepartment')->getDepartmentData($departmentIds);
			$quartersData = $this->service('OrganizationQuarters')->getQuartersData($quartersIds);
			$positionData = $this->service('OrganizationPosition')->getPositionData($positionIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['company'] = isset($companyData[$data['company_identity']])?$companyData[$data['company_identity']]:array();	
				$listdata[$key]['department'] = isset($departmentData[$data['department_identity']])?$departmentData[$data['department_identity']]:array();
				$listdata[$key]['quarters'] = isset($quartersData[$data['quarters_identity']])?$quartersData[$data['quarters_identity']]:array();
				$listdata[$key]['position'] = isset($positionData[$data['position_identity']])?$positionData[$data['position_identity']]:array();			
			}
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 布局信息
	 *
	 * @param $employeeId 布局ID
	 *
	 * @reutrn array;
	 */
	public function getEmployeeInfo($employeeId,$field = '*'){
		
		$where = array(
			'identity'=>$employeeId
		);
		
		$employeeData = $this->model('OrganizationEmployee')->field($field)->where($where)->find();
		
		return $employeeData;
	}
	
	/**
	 *
	 * 布局信息
	 *
	 * @param $employeeId 布局ID
	 *
	 * @reutrn array;
	 */
	public function getEmployeeBaseInfo($employeeId){
		
		$where = array(
			'identity'=>$employeeId
		);
		
		$employeeData = $this->model('OrganizationEmployee')->where($where)->find();
		
		return $employeeData;
	}
	
	/**
	 *
	 * 删除布局
	 *
	 * @param $employeeId 布局ID
	 *
	 * @reutrn int;
	 */
	public function removeEmployeeId($employeeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$employeeId
		);
		
		$employeeData = $this->model('OrganizationEmployee')->where($where)->find();
		if($employeeData){
			
			$output = $this->model('OrganizationEmployee')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 布局修改
	 *
	 * @param $employeeId 布局ID
	 * @param $employeeNewData 布局数据
	 *
	 * @reutrn int;
	 */
	public function update($employeeNewData,$employeeId){
		$where = array(
			'identity'=>$employeeId
		);
		
		$employeeData = $this->model('OrganizationEmployee')->where($where)->find();
		if($employeeData){
			if(!$employeeNewData['company_identity']){
				$employeeNewData['company_identity'] = $employeeData['company_identity'];
			}
			$employeeNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationEmployee')->data($employeeNewData)->where($where)->save();
			if($employeeData['quarters_identity'] != $employeeNewData['quarters_identity']){
				$roleId = $this->service('OrganizationQuarters')->getRoleIdByQuartersId($employeeNewData['quarters_identity']);
			}
		}
	}
	
	/**
	 *
	 * 新布局
	 *
	 * @param $employeeNewData 布局数据
	 *
	 * @reutrn int;
	 */
	public function insert($employeeNewData){
		
		$employeeNewData['subscriber_identity'] =$this->session('uid');
		$employeeNewData['dateline'] = $this->getTime();
			
		$employeeNewData['lastupdate'] = $employeeNewData['dateline'];
		$employeeNewData['sn'] = $this->get_sn();
		if(!$employeeNewData['company_identity']){
			$employeeNewData['company_identity'] = $this->session('company_identity');
		}
		$lastInsertId = $this->model('OrganizationEmployee')->data($employeeNewData)->add();
		if($lastInsertId){
			$roleId = $this->service('OrganizationQuarters')->getRoleIdByQuartersId($employeeNewData['quarters_identity']);
			
			$this->service('AuthoritySubscriber')->newEmployeeUser($lastInsertId,$employeeNewData['mobile'],$employeeNewData['fullname'],$roleId);
		}
	}
}