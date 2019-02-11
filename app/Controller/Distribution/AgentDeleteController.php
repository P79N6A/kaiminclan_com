<?php
/**
 *
 * 删除代理
 *
 * 20180301
 *
 */
class AgentDeleteController extends Controller {
	
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
		
		$agentInfo = $this->service('DistributionAgent')->getAgentInfo($agentId);
		
		if(!$agentInfo){
			$this->info('代理不存在',4101);
		}
		if(!is_array($agentId)){
			$agentInfo = array($agentInfo);
		}
		
		$removeAgentIds = array();
		foreach($agentInfo as $key=>$agent){
				$removeAgentIds[] = $agent['identity'];
		}
		
		$this->service('DistributionAgent')->removeAgentId($removeAgentIds);
		
		$sourceTotal = count($agentId);
		$successNum = count($removeAgentIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>