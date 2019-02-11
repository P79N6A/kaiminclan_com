<?php
/**
 *
 * 转入启用
 *
 * 20180301
 *
 */
class RevenueEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'revenueId'=>array('type'=>'digital','tooltip'=>'转入ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$revenueId = $this->argument('revenueId');
		
		$groupInfo = $this->service('BankrollRevenue')->getCatalogInfo($revenueId);
		if(!$groupInfo){
			$this->info('转入不存在',4101);
		}
		
		if($groupInfo['status'] == BankrollRevenueModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('BankrollRevenue')->update(array('status'=>BankrollRevenueModel::PAGINATION_BLOCK_STATUS_ENABLE),$revenueId);
		}
		
		
	}
}
?>