<?php
/**
 *
 * 文档启用
 *
 * 20180301
 *
 */
class DocumentationEnableController extends Controller {
	
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
		
		$documentationId = $this->argument('documentationId');
		
		$documentationInfo = $this->service('KnowledgeDocumentation')->getDocumentationInfo($documentationId);
		if(!$documentationInfo){
			$this->info('文档不存在',4101);
		}
		
		if($documentationInfo['status'] == KnowledgeDocumentationModel::INTERCALATE_SUPERVISE_STATUS_DISABLED){
			$this->service('KnowledgeDocumentation')->update(array('status'=>KnowledgeDocumentationModel::INTERCALATE_SUPERVISE_STATUS_ENABLE),$documentationId);
		}
		
		
	}
}
?>