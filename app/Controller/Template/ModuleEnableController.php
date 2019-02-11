<?php
/**
 *
 * 模板启用
 *
 * 20180301
 *
 */
class ModuleEnableController extends Controller {
	
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
		
		if($groupInfo['status'] == TemplateModuleModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('TemplateModule')->update(array('status'=>TemplateModuleModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$moduleId);
		}
		
		
	}
}
?>