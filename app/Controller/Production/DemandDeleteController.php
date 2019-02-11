<?php
/**
 *
 * 删除需求
 *
 * 20180301
 *
 */
class DemandDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'demandId'=>array('type'=>'digital','tooltip'=>'需求ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$demandId = $this->argument('demandId');
		
		$groupInfo = $this->service('ProductionDemand')->getDemandInfo($demandId);
		
		if(!$groupInfo){
			$this->info('需求不存在',4101);
		}
		if(!is_array($demandueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ProductionDemand')->removeDemandId($removeGroupIds);
		
		$sourceTotal = count($demandueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>