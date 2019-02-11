<?php
/**
 *
 * 删除部门
 *
 * 20180301
 *
 */
class DepartmentDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'departmentId'=>array('type'=>'digital','tooltip'=>'部门ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$departmentId = $this->argument('departmentId');
		
		$departmentList = $this->service('OrganizationDepartment')->getDepartmentInfo($departmentId);
		
		if(!$departmentList){
			$this->info('部门不存在',4101);
		}
		if(!is_array($departmentId)){
			$departmentList = array($departmentList);
		}
		
		$removeDepartmentIds = array();
		foreach($departmentList as $key=>$department){
			if($department['employee_num'] < 1 && $department['position_num'] < 1 && $department['quarters_num'] < 1){
				$removeDepartmentIds[] = $department['identity'];
			}
		}
		
		$this->service('OrganizationDepartment')->removeDepartmentId($removeDepartmentIds);
		
		
	}
}
?>