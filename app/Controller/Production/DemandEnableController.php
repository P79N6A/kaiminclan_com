<?php
/**
 *
 * 需求启用
 *
 * 20180301
 *
 */
class DemandEnableController extends Controller {
	
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
		
		if($groupInfo['status'] == ProductionDemandModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('ProductionDemand')->update(array('status'=>ProductionDemandModel::PAGINATION_BLOCK_STATUS_ENABLE),$demandId);
		}
		
		
	}
}
?>