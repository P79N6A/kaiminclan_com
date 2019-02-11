<?php
/**
 *
 * 禁用职员
 *
 * 20180301
 *
 */
class EmployeeDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'employeeId'=>array('type'=>'digital','tooltip'=>'职员ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$employeeId = $this->argument('employeeId');
		
		$groupInfo = $this->service('OrganizationEmployee')->getEmployeeInfo($employeeId);
		if(!$groupInfo){
			$this->info('职员不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationEmployeeModel::PAGINATION_LAYOUT_STATUS_ENABLE){
			$this->service('OrganizationEmployee')->update(array('status'=>OrganizationEmployeeModel::PAGINATION_LAYOUT_STATUS_DISABLED),$employeeId);
		}
	}
}
?>