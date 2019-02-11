<?php
/**
 *
 * 删除页面
 *
 * 20180301
 *
 */
class FrontendDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'frontendId'=>array('type'=>'digital','tooltip'=>'页面ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$frontendId = $this->argument('frontendId');
		
		$groupInfo = $this->service('ProductionFrontend')->getFrontendInfo($frontendId);
		
		if(!$groupInfo){
			$this->info('页面不存在',4101);
		}
		if(!is_array($frontendueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ProductionFrontend')->removeFrontendId($removeGroupIds);
		
		$sourceTotal = count($frontendueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>