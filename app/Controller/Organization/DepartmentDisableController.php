<?php
/**
 *
 * 禁用部门
 *
 * 20180301
 *
 */
class DepartmentDisableController extends Controller {
	
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
		
		$groupInfo = $this->service('OrganizationDepartment')->getDepartmentInfo($departmentId);
		if(!$groupInfo){
			$this->info('部门不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationDepartmentModel::PAGINATION_ITEM_STATUS_ENABLE){
			$this->service('OrganizationDepartment')->update(array('status'=>OrganizationDepartmentModel::PAGINATION_ITEM_STATUS_DISABLED),$departmentId);
		}
	}
}
?>