<?php
/**
 *
 * 岗位
 *
 *
 */
class OrganizationProspectusService extends Service
{
	
	/**
	 *
	 * 岗位信息
	 *
	 * @param $field 岗位字段
	 * @param $status 岗位状态
	 *
	 * @reutrn array;
	 */
	public function getProspectusList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('OrganizationProspectus')->where($where)->count();
		if($count){
			$selectHandel = $this->model('OrganizationProspectus')->where($where)->orderby($order);
			if($perpage > 0){
				$selectHandel ->limit($start,$perpage,$count);
			}
			
			$listdata = $selectHandel->select();
			$companyIds = $departmentIds = $prospectusIds = $positionIds = array();
			foreach($listdata as $key=>$data){
				$companyIds[] = $data['company_identity'];		
				$departmentIds[] = $data['department_identity'];	
				$employeeIds[] = $data['employee_identity'];		
			}
			
			$companyData = $this->service('OrganizationCompany')->getCompanyData($companyIds);
			$departmentData = $this->service('OrganizationDepartment')->getDepartmentData($departmentIds);
			$employeeData = $this->service('OrganizationEmployee')->getEmployeeData($employeeIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['company'] = isset($companyData[$data['company_identity']])?$companyData[$data['company_identity']]:array();	
				$listdata[$key]['department'] = isset($departmentData[$data['department_identity']])?$departmentData[$data['department_identity']]:array();
				$listdata[$key]['employee'] = isset($employeeData[$data['employee_identity']])?$employeeData[$data['employee_identity']]:array();
			}
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 部门信息
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getProspectusData($companyId){
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationProspectus')->field('identity,title')->where($where)->select();
		
		return $companyData;
	}
	
	/**
	 *
	 * 岗位信息
	 *
	 * @param $prospectusId 岗位ID
	 *
	 * @reutrn array;
	 */
	public function getProspectusInfo($prospectusId,$field = '*'){
		
		$where = array(
			'identity'=>$prospectusId
		);
		
		$prospectusData = $this->model('OrganizationProspectus')->field($field)->where($where)->find();
		
		return $prospectusData;
	}
	
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkProspectusTitle($title,$employee_identity){
		if($title){
			$where = array(
				'title'=>$title,
				'employee_identity'=>$employee_identity
			);
			return $this->model('OrganizationProspectus')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除岗位
	 *
	 * @param $prospectusId 岗位ID
	 *
	 * @reutrn int;
	 */
	public function removeProspectusId($prospectusId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$prospectusId
		);
		
		$prospectusData = $this->model('OrganizationProspectus')->where($where)->find();
		if($prospectusData){
			
			$output = $this->model('OrganizationProspectus')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 岗位修改
	 *
	 * @param $prospectusId 岗位ID
	 * @param $prospectusNewData 岗位数据
	 *
	 * @reutrn int;
	 */
	public function update($prospectusNewData,$prospectusId){
		$where = array(
			'identity'=>$prospectusId
		);
		
		$prospectusData = $this->model('OrganizationProspectus')->where($where)->find();
		if($prospectusData){
			
			$prospectusNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationProspectus')->data($prospectusNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新岗位
	 *
	 * @param $prospectusNewData 岗位数据
	 *
	 * @reutrn int;
	 */
	public function insert($prospectusNewData){
		
		$prospectusNewData['subscriber_identity'] =$this->session('uid');
		$prospectusNewData['dateline'] = $this->getTime();
			
		$prospectusNewData['lastupdate'] = $prospectusNewData['dateline'];
		$prospectusNewData['sn'] = $this->get_sn();
		
		$prospectusId = $this->model('OrganizationProspectus')->data($prospectusNewData)->add();
		return $prospectusId;
	}
}