<?php
/**
 *
 * 禁用目标
 *
 * 20180301
 *
 */
class ProjectDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'projectId'=>array('type'=>'digital','tooltip'=>'目标ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$projectId = $this->argument('projectId');
		
		$groupInfo = $this->service('BudgetProject')->getTemplateInfo($projectId);
		if(!$groupInfo){
			$this->info('目标不存在',4101);
		}
		
		if($groupInfo['status'] == BudgetProjectModel::PAGINATION_TEMPLATE_STATUS_ENABLE){
			$this->service('BudgetProject')->update(array('status'=>BudgetProjectModel::PAGINATION_TEMPLATE_STATUS_DISABLED),$projectId);
		}
	}
}
?>