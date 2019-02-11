<?php
/**
 *
 * 删除账户
 *
 * 20180301
 *
 */
class AccountDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'accountId'=>array('type'=>'digital','tooltip'=>'账户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$accountId = $this->argument('accountId');
		
		$groupInfo = $this->service('MechanismAccount')->getAccountInfo($accountId);
		
		if(!$groupInfo){
			$this->info('账户不存在',4101);
		}
		if(!is_array($accountueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('MechanismAccount')->removeAccountId($removeGroupIds);
		
		$sourceTotal = count($accountueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>