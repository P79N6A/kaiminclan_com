<?php
/**
 *
 * 禁用测试用例
 *
 * 20180301
 *
 */
class ExampleDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'revenueId'=>array('type'=>'digital','tooltip'=>'测试用例ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$revenueId = $this->argument('revenueId');
		
		$groupInfo = $this->service('FaultinessExample')->getExampleInfo($revenueId);
		if(!$groupInfo){
			$this->info('测试用例不存在',4101);
		}
		
		if($groupInfo['status'] == FaultinessExampleModel::FAULTINESS_EXAMPLE_STATUS_ENABLE){
			$this->service('FaultinessExample')->update(array('status'=>FaultinessExampleModel::FAULTINESS_EXAMPLE_STATUS_DISABLED),$revenueId);
		}
	}
}
?>