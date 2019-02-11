<?php
/**
 *
 * 删除目标
 *
 * 20180301
 *
 */
class ProjectDeleteController extends Controller {
	
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
		
		$groupInfo = $this->service('BudgetProject')->getProjectInfo($projectId);
		
		if(!$groupInfo){
			$this->info('目标不存在',4101);
		}
		if(!is_array($projectueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('BudgetProject')->removeProjectId($removeGroupIds);
		
		$sourceTotal = count($projectueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>