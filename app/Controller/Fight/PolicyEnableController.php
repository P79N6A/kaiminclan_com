<?php
/**
 *
 * 产品启用
 *
 * 20180301
 *
 */
class PolicyEnableController extends Controller {
	
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
		
		$groupInfo = $this->service('FightPolicy')->getPolicyInfo($policyId);
		if(!$groupInfo){
			$this->info('产品不存在',4101);
		}
		
		if($groupInfo['status'] == FightPolicyModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FightPolicy')->update(array('status'=>FightPolicyModel::PAGINATION_BLOCK_STATUS_ENABLE),$policyId);
		}
		
		
	}
}
?>