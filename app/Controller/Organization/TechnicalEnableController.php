<?php
/**
 *
 * 职称启用
 *
 * 20180301
 *
 */
class TechnicalEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'templateId'=>array('type'=>'digital','tooltip'=>'职称ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$templateId = $this->argument('templateId');
		
		$groupInfo = $this->service('OrganizationTechnical')->getTemplateInfo($templateId);
		if(!$groupInfo){
			$this->info('职称不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationTechnicalModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('OrganizationTechnical')->update(array('status'=>OrganizationTechnicalModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$templateId);
		}
		
		
	}
}
?>