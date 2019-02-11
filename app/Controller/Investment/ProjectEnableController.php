<?php
/**
 *
 * 项目启用
 *
 * 20180301
 *
 */
class ProjectEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'projectId'=>array('type'=>'digital','tooltip'=>'项目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$projectId = $this->argument('projectId');
		
		$groupInfo = $this->service('InvestmentProject')->getProjectInfo($projectId);
		if(!$groupInfo){
			$this->info('项目不存在',4101);
		}
		
		if($groupInfo['status'] == InvestmentProjectModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('InvestmentProject')->update(array('status'=>InvestmentProjectModel::PAGINATION_BLOCK_STATUS_ENABLE),$projectId);
		}
		
		
	}
}
?>