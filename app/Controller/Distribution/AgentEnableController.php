<?php
/**
 *
 * 代理启用
 *
 * 20180301
 *
 */
class AgentEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'agentId'=>array('type'=>'digital','tooltip'=>'代理ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$agentId = $this->argument('agentId');
		
		$groupInfo = $this->service('DistributionAgent')->getAgentInfo($agentId);
		if(!$groupInfo){
			$this->info('代理不存在',4101);
		}
		
		if($groupInfo['status'] == DistributionAgentModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('DistributionAgent')->update(array('status'=>DistributionAgentModel::PAGINATION_BLOCK_STATUS_ENABLE),$agentId);
		}
		
		
	}
}
?>