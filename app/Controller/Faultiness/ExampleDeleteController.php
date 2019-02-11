<?php
/**
 *
 * 删除测试用例
 *
 * 20180301
 *
 */
class ExampleDeleteController extends Controller {
	
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
		if(!is_array($revenueueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('FaultinessExample')->removeExampleId($removeGroupIds);
		
		$sourceTotal = count($revenueueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>