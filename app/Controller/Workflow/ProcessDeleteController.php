<?php
/**
 *
 * 删除流程
 *
 * 20180301
 *
 */
class ProcessDeleteController extends Controller {
	
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
		if(!is_array($processueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('WorkflowProcess')->removeProcessId($removeGroupIds);
		
		$sourceTotal = count($processueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>