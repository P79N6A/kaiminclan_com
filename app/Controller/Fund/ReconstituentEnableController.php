<?php
/**
 *
 * 成份启用
 *
 * 20180301
 *
 */
class ReconstituentEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'reconstituentId'=>array('type'=>'digital','tooltip'=>'成份ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$reconstituentId = $this->argument('reconstituentId');
		
		$groupInfo = $this->service('FundReconstituent')->getReconstituentInfo($reconstituentId);
		if(!$groupInfo){
			$this->info('成份不存在',4101);
		}
		
		if($groupInfo['status'] == FundReconstituentModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FundReconstituent')->update(array('status'=>FundReconstituentModel::PAGINATION_BLOCK_STATUS_ENABLE),$reconstituentId);
		}
		
		
	}
}
?>