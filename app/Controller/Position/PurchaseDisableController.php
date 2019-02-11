<?php
/**
 *
 * 禁用开仓
 *
 * 20180301
 *
 */
class PurchaseDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'purchaseId'=>array('type'=>'digital','tooltip'=>'开仓ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$purchaseId = $this->argument('purchaseId');
		
		$groupInfo = $this->service('PositionPurchase')->getCatalogInfo($purchaseId);
		if(!$groupInfo){
			$this->info('开仓不存在',4101);
		}
		
		if($groupInfo['status'] == PositionPurchaseModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('PositionPurchase')->update(array('status'=>PositionPurchaseModel::PAGINATION_BLOCK_STATUS_DISABLED),$purchaseId);
		}
	}
}
?>