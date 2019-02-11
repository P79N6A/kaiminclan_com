<?php
/**
 *
 * 删除授信
 *
 * 20180301
 *
 */
class CreditDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'creditId'=>array('type'=>'digital','tooltip'=>'授信ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$creditId = $this->argument('creditId');
		
		$groupInfo = $this->service('PermanentCredit')->getCreditInfo($creditId);
		
		if(!$groupInfo){
			$this->info('授信不存在',4101);
		}
		if(!is_array($creditueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('PermanentCredit')->removeCreditId($removeGroupIds);
		
		$sourceTotal = count($creditueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>