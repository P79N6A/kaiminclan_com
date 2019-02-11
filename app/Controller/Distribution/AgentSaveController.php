<?php
/**
 *
 * 代理编辑
 *
 * 20180301
 *
 */
class AgentSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'agentId'=>array('type'=>'digital','tooltip'=>'代理ID','default'=>0),
			'parent_agent_identity'=>array('type'=>'string','tooltip'=>'隶属代理','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'grade_identity'=>array('type'=>'string','tooltip'=>'目录'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$agentId = $this->argument('agentId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'agent_identity' => $this->argument('parent_agent_identity'),
			'grade_identity' => $this->argument('grade_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($agentId){
			$this->service('DistributionAgent')->update($setarr,$agentId);
		}else{
			
			if($this->service('DistributionAgent')->checkAgentTitle($setarr['title'])){
				
				$this->info('代理已存在',4001);
			}
			
			$this->service('DistributionAgent')->insert($setarr);
		}
	}
}
?>