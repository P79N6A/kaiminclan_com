<?php
/**
 *
 * 收银
 *
 * 20180301
 *
 */
class OrderddCheckoutController extends Controller {
	
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
		
		$groupInfo = $this->service('DealingsAdjustment')->getAdjustmentInfo($adjustmentId);
		
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		if(!is_array($adjustmentueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('DealingsAdjustment')->removeAdjustmentId($removeGroupIds);
		
		$sourceTotal = count($adjustmentueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>