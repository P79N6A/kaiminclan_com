<?php
/**
 *
 * 删除文档
 *
 * 20180301
 *
 */
class DocumentationDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentationId'=>array('type'=>'digital','tooltip'=>'文档ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeDocumentationIds = $this->argument('documentationId');
		
		$documentationInfo = $this->service('KnowledgeDocumentation')->getDocumentationInfo($removeDocumentationIds);
		
		if(!$documentationInfo){
			$this->info('文档不存在',4101);
		}
		
		$this->service('KnowledgeDocumentation')->removeDocumentationId($removeDocumentationIds);
		
		$sourceTotal = count($documentationId);
		$successNum = count($removeDocumentationIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>