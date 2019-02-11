<?php
/**
 *
 * 禁用应付款
 *
 * 20180301
 *
 */
class PayableDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'payableId'=>array('type'=>'digital','tooltip'=>'应付款ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$payableId = $this->argument('payableId');
		
		$groupInfo = $this->service('PermanentPayable')->getPayableInfo($payableId);
		if(!$groupInfo){
			$this->info('应付款不存在',4101);
		}
		
		if($groupInfo['status'] == PermanentPayableModel::PAGINATION_LAYOUT_STATUS_ENABLE){
			$this->service('PermanentPayable')->update(array('status'=>PermanentPayableModel::PAGINATION_LAYOUT_STATUS_DISABLED),$payableId);
		}
	}
}
?>