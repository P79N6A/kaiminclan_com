<?php
/**
 *
 * 删除调账
 *
 * 20180301
 *
 */
class AdjustmentDeleteController extends Controller {
	
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
		
		$removeAdjustmentIds = $this->argument('adjustmentId');
		
		$groupInfo = $this->service('BankrollAdjustment')->getAdjustmentInfo($removeAdjustmentIds);
		
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		
		$this->service('BankrollAdjustment')->removeAdjustmentId($removeAdjustmentIds);
		
		$sourceTotal = count($adjustmentId);
		$successNum = count($removeAdjustmentIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>