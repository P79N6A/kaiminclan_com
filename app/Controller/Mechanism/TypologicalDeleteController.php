<?php
/**
 *
 * 删除账户类型
 *
 * 20180301
 *
 */
class TypologicalDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'typologicalId'=>array('type'=>'digital','tooltip'=>'账户类型ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$typologicalId = $this->argument('typologicalId');
		
		$groupInfo = $this->service('MechanismTypological')->getTypologicalInfo($typologicalId);
		
		if(!$groupInfo){
			$this->info('账户类型不存在',4101);
		}
		if(!is_array($typologicalueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('MechanismTypological')->removeTypologicalId($removeGroupIds);
		
		$sourceTotal = count($typologicalueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>