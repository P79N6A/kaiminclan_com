<?php
/**
 *
 * 删除银行卡
 *
 * 20180301
 *
 */
class BankcardDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bankcardId'=>array('type'=>'digital','tooltip'=>'银行卡ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bankcardId = $this->argument('bankcardId');
		
		$groupInfo = $this->service('MechanismBankcard')->getBankcardInfo($bankcardId);
		
		if(!$groupInfo){
			$this->info('银行卡不存在',4101);
		}
		if(!is_array($bankcardueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('MechanismBankcard')->removeBankcardId($removeGroupIds);
		
		$sourceTotal = count($bankcardueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>