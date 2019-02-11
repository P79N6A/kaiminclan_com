<?php
/**
 *
 * 删除执行
 *
 * 20180301
 *
 */
class ProcedureDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'procedureId'=>array('type'=>'digital','tooltip'=>'执行ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$procedureId = $this->argument('procedureId');
		
		$groupInfo = $this->service('BudgetProcedure')->getProcedureInfo($procedureId);
		
		if(!$groupInfo){
			$this->info('执行不存在',4101);
		}
		if(!is_array($procedureueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('BudgetProcedure')->removeProcedureId($removeGroupIds);
		
		$sourceTotal = count($procedureueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>