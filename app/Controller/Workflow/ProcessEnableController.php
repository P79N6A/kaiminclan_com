<?php
/**
 *
 * 流程启用
 *
 * 20180301
 *
 */
class ProcessEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'processId'=>array('type'=>'digital','tooltip'=>'流程ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$processId = $this->argument('processId');
		
		$groupInfo = $this->service('WorkflowProcess')->getProcessInfo($processId);
		if(!$groupInfo){
			$this->info('流程不存在',4101);
		}
		
		if($groupInfo['status'] == WorkflowProcessModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('WorkflowProcess')->update(array('status'=>WorkflowProcessModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$processId);
		}
		
		
	}
}
?>