<?php
/**
 *
 * 产品编辑
 *
 * 20180301
 *
 */
class TemplateSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'templateId'=>array('type'=>'digital','tooltip'=>'产品ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'模板','length'=>200),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>‘’)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$templateId = $this->argument('templateId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark')
		);
		
		if($templateId){
			$this->service('MessengerTemplate')->update($setarr,$templateId);
		}else{
			
			if($this->service('MessengerTemplate')->checkTemplateTitle($setarr['title'])){
				
				$this->info('产品已存在',4001);
			}
			
			$this->service('MessengerTemplate')->insert($setarr);
		}
	}
}
?>