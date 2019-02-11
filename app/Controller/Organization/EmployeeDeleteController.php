<?php
/**
 *
 * 删除职员
 *
 * 20180301
 *
 */
class EmployeeDeleteController extends Controller {
	
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
		
		$this->service('OrganizationEmployee')->removeEmployeeId($employeeId);
		
		$sourceTotal = count($employeeueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>