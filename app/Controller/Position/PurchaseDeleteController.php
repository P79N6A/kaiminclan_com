<?php
/**
 *
 * 删除开仓
 *
 * 20180301
 *
 */
class PurchaseDeleteController extends Controller {
	
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
		
		$removePurchaseIds = $this->argument('purchaseId');
		
		$groupInfo = $this->service('PositionPurchase')->getPurchaseInfo($removePurchaseIds);
		
		if(!$groupInfo){
			$this->info('开仓不存在',4101);
		}
		
		$this->service('PositionPurchase')->removePurchaseId($removePurchaseIds);
		
		$sourceTotal = count($purchaseId);
		$successNum = count($removePurchaseIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>