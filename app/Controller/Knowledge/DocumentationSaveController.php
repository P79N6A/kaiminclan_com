<?php
/**
 *
 * 文档编辑
 *
 * 20180301
 *
 */
class DocumentationSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentationId'=>array('type'=>'digital','tooltip'=>'文档ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'knowhow_identity'=>array('type'=>'digital','tooltip'=>'知识ID'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$documentationId = $this->argument('documentationId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'knowhow_identity' => $this->argument('knowhow_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($documentationId){
			$this->service('KnowledgeDocumentation')->update($setarr,$documentationId);
		}else{
			if($this->service('KnowledgeDocumentation')->checkDocumentationTitle($setarr['title'],$setarr['knowhow_identity'])){
				$this->info('此文档已存在',40012);
			}
			$this->service('KnowledgeDocumentation')->insert($setarr);
		}
	}
}
?>