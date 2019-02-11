<?php
/**
 *
 * 删除文章
 *
 * 20180301
 *
 */
class DocumentationDeleteController extends Controller {
	
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
		if(!is_array($documentationueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('IntelligenceDocumentation')->removeDocumentationId($removeGroupIds);
		
		$sourceTotal = count($documentationueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>