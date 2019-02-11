<?php
/**
 *
 * 禁用收款
 *
 * 20180301
 *
 */
class RevenueDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'revenueId'=>array('type'=>'digital','tooltip'=>'收款ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$revenueId = $this->argument('revenueId');
		
		$groupInfo = $this->service('DealingsRevenue')->getRevenueInfo($revenueId);
		if(!$groupInfo){
			$this->info('收款不存在',4101);
		}
		
		if($groupInfo['status'] == DealingsRevenueModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('DealingsRevenue')->update(array('status'=>DealingsRevenueModel::PAGINATION_BLOCK_STATUS_DISABLED),$revenueId);
		}
	}
}
?>