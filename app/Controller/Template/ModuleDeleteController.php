<?php
/**
 *
 * 删除模板
 *
 * 20180301
 *
 */
class ModuleDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'moduleId'=>array('type'=>'digital','tooltip'=>'模板ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$moduleId = $this->argument('moduleId');
		
		$groupInfo = $this->service('TemplateModule')->getModuleInfo($moduleId);
		
		if(!$groupInfo){
			$this->info('模板不存在',4101);
		}
		if(!is_array($moduleueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('TemplateModule')->removeModuleId($removeGroupIds);
		
		$sourceTotal = count($moduleueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>