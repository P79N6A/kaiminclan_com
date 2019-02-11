<?php
/**
 *
 * 文章编辑
 *
 * 20180301
 *
 */
class DocumentationSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentationId'=>array('type'=>'digital','tooltip'=>'文章ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'html','tooltip'=>'内容'),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'栏目'),
			'originate_identity'=>array('type'=>'digital','tooltip'=>'来源','default'=>0),
			'summary'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$documentationId = $this->argument('documentationId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'originate_identity' => $this->argument('originate_identity'),
			'summary' => $this->argument('summary')
		);
		
		if($documentationId){
			$this->service('IntelligenceDocumentation')->update($setarr,$documentationId);
		}else{
			
			if($this->service('IntelligenceDocumentation')->checkDocumentationTitle($setarr['title'])){
				
				$this->info('文章已存在',4001);
			}
			
			$this->service('IntelligenceDocumentation')->insert($setarr);
		}
	}
}
?>