<?php
/**
 *
 * 删除支出
 *
 * 20180301
 *
 */
class ExpensesDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'expensesId'=>array('type'=>'digital','tooltip'=>'支出ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$expensesId = $this->argument('expensesId');
		
		$groupInfo = $this->service('DealingsExpenses')->getExpensesInfo($expensesId);
		
		if(!$groupInfo){
			$this->info('支出不存在',4101);
		}
		if(!is_array($expensesueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('DealingsExpenses')->removeExpensesId($removeGroupIds);
		
		$sourceTotal = count($expensesueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>