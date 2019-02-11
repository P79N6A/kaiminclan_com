<?php
/**
 *
 * 删除模块
 *
 * 20180301
 *
 */
class ThemeDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'themeId'=>array('type'=>'digital','tooltip'=>'模块ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$themeId = $this->argument('themeId');
		
		$groupInfo = $this->service('TemplateTheme')->getThemeInfo($themeId);
		
		if(!$groupInfo){
			$this->info('模块不存在',4101);
		}
		if(!is_array($themeueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('TemplateTheme')->removeThemeId($removeGroupIds);
		
		$sourceTotal = count($themeueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>