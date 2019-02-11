<?php
/**
 *
 * 删除附件
 *
 * 资源库
 *
 * 20180301
 *
 */
class DocumentDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentId'=>array('type'=>'digital','tooltip'=>'附件ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$documentId = $this->argument('documentId');
		
		$removeAttachmentList = $this->service('AttachmentDocument')->getAttachUrl($documentId);
		
		if(!$removeAttachmentList){
			$this->info('附件不存在',4101);
		}
		
		$removeAttachmentUrl = $removeAttachmentIds = array();
		
		foreach($removeAttachmentList as $aid =>$url){
			$removeAttachmentUrl[] = $url;
			$removeAttachmentIds[] = $aid;
		}
	
		
		$this->service('AttachmentDocument')->removeAttachmentId($removeAttachmentIds);
		
		foreach($removeAttachmentUrl as $key=>$url){
			
			$this->service('ResourcesUpload')->delete($url);
			
		}
		
	}
}
?>