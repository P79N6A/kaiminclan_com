<?php
/**
 *
 * 删除测试用例
 *
 * 20180301
 *
 */
class ReleaseDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'releaseId'=>array('type'=>'digital','tooltip'=>'测试用例ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$releaseId = $this->argument('releaseId');
		
		$groupInfo = $this->service('FaultinessRelease')->getReleaseInfo($releaseId);
		
		if(!$groupInfo){
			$this->info('测试用例不存在',4101);
		}
		if(!is_array($releaseueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('FaultinessRelease')->removeReleaseId($removeGroupIds);
		
		$sourceTotal = count($releaseueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>