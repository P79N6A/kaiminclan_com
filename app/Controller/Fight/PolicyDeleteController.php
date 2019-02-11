<?php
/**
 *
 * 删除产品
 *
 * 20180301
 *
 */
class PolicyDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'policyId'=>array('type'=>'digital','tooltip'=>'产品ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$policyId = $this->argument('policyId');
		
		$policyInfo = $this->service('FightPolicy')->getPolicyInfo($policyId);
		
		if(!$policyInfo){
			$this->info('产品不存在',4101);
		}
		
		if(!is_array($policyId)){
			$policyInfo = array($policyInfo);
		}
		
		
		$removePolicyIds = array();
		foreach($policyInfo as $key=>$policy){
			$removePolicyIds[] = $policy['identity'];
		}
		
		$this->service('FightPolicy')->removePolicyId($removePolicyIds);
		
		$sourceTotal = count($policyId);
		$successNum = count($removePolicyIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>