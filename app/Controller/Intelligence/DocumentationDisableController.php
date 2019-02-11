<?php
/**
 *
 * 禁用文章
 *
 * 20180301
 *
 */
class DocumentationDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentationId'=>array('type'=>'digital','tooltip'=>'文章ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$documentationId = $this->argument('documentationId');
		
		$groupInfo = $this->service('IntelligenceDocumentation')->getDocumentationInfo($documentationId);
		if(!$groupInfo){
			$this->info('文章不存在',4101);
		}
		
		if($groupInfo['status'] == IntelligenceDocumentationModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('IntelligenceDocumentation')->update(array('status'=>IntelligenceDocumentationModel::PAGINATION_BLOCK_STATUS_DISABLED),$documentationId);
		}
	}
}
?>