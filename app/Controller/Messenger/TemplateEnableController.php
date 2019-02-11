<?php
/**
 *
 * 产品启用
 *
 * 20180301
 *
 */
class TemplateEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'templateId'=>array('type'=>'digital','tooltip'=>'产品ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$templateId = $this->argument('templateId');
		
		$groupInfo = $this->service('MessengerTemplate')->getTemplateInfo($templateId);
		if(!$groupInfo){
			$this->info('产品不存在',4101);
		}
		
		if($groupInfo['status'] == MessengerTemplateModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('MessengerTemplate')->update(array('status'=>MessengerTemplateModel::PAGINATION_BLOCK_STATUS_ENABLE),$templateId);
		}
		
		
	}
}
?>