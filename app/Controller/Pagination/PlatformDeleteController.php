<?php
/**
 *
 * 删除平台
 *
 * 20180301
 *
 */
class PlatformDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'platformId'=>array('type'=>'digital','tooltip'=>'平台ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$platformId = $this->argument('platformId');
		
		$groupInfo = $this->service('FoundationPlatform')->getPlatformInfo($platformId);
		
		if(!$groupInfo){
			$this->info('平台不存在',4101);
		}
		if(!is_array($platformId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('FoundationPlatform')->removePlatformId($removeGroupIds);
		
		$sourceTotal = count($platformId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>