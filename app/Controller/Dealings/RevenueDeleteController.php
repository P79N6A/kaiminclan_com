<?php
/**
 *
 * 删除收款
 *
 * 20180301
 *
 */
class RevenueDeleteController extends Controller {
	
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
		if(!is_array($revenueueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('DealingsRevenue')->removeRevenueId($removeGroupIds);
		
		$sourceTotal = count($revenueueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>