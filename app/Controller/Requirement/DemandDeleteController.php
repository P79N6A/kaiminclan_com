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
		
		$demandInfo = $this->service('RequirementDemand')->getDemandInfo($demandId);
		
		if(!$demandInfo){
			$this->info('需求不存在',4101);
		}
		if(!is_array($demandId)){
			$demandInfo = array($demandInfo);
		}
		
		$removeGroupIds = array();
		foreach($demandInfo as $key=>$demand){
				$removeGroupIds[] = $demand['identity'];
		}
		
		$this->service('RequirementDemand')->removeDemandId($removeGroupIds);
		
		$sourceTotal = count($demandId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>