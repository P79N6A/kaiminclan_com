<?php
/**
 *
 * 附件编辑
 *
 * 资源库
 *
 * 20180301
 *
 */
class DocumentSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentId'=>array('type'=>'digital','tooltip'=>'附件ID'),
			'catalog_identity'=>array('type'=>'digtial','tooltip'=>'目录'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>ResourcesServerModel::RESOURCES_ATTACHMENT_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$documentId = $this->argument('documentId');
		
		$documentData = array(
			'catalog_identity' => $this->argument('catalog_identity'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
			$this->service('AttachmentDocument')->update($documentData,$documentId);
	}
}
?>