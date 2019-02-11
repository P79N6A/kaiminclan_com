<?php
/**
 *
 * 删除转入
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
			'revenueId'=>array('type'=>'digital','tooltip'=>'转入ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeRevenueIds = $this->argument('revenueId');
		
		$groupInfo = $this->service('BankrollRevenue')->getRevenueInfo($removeRevenueIds);
		
		if(!$groupInfo){
			$this->info('转入不存在',4101);
		}
		
		$this->service('BankrollRevenue')->removeRevenueId($removeRevenueIds);
		
		$sourceTotal = count($revenueId);
		$successNum = count($removeRevenueIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>