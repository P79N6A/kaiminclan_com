<?php
/**
 *
 * 删除产品
 *
 * 20180301
 *
 */
class TemplateDeleteController extends Controller {
	
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
		
		$removeTemplateIds = $this->argument('templateId');
		
		$templateInfo = $this->service('MessengerTemplate')->getTemplateInfo($removeTemplateIds);
		
		if(!$templateInfo){
			$this->info('产品不存在',4101);
		}
		if(!is_array($templateueId)){
			$templateInfo = array($templateInfo);
		}

		
		$this->service('MessengerTemplate')->removeTemplateId($removeTemplateIds);
		
		$sourceTotal = count($templateueId);
		$successNum = count($removeTemplateIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>