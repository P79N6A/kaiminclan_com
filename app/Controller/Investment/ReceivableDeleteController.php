<?php
/**
 *
 * 删除应付款
 *
 * 20180301
 *
 */
class PayableDeleteController extends Controller {
	
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
		if(!is_array($payableueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('PermanentPayable')->removePayableId($removeGroupIds);
		
		$sourceTotal = count($payableueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>