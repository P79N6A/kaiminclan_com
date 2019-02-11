<?php
/**
 *
 * 调账启用
 *
 * 20180301
 *
 */
class AdjustmentEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'adjustmentId'=>array('type'=>'digital','tooltip'=>'调账ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$adjustmentId = $this->argument('adjustmentId');
		
		$groupInfo = $this->service('BankrollAdjustment')->getCatalogInfo($adjustmentId);
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		
		if($groupInfo['status'] == BankrollAdjustmentModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('BankrollAdjustment')->update(array('status'=>BankrollAdjustmentModel::PAGINATION_BLOCK_STATUS_ENABLE),$adjustmentId);
		}
		
		
	}
}
?>