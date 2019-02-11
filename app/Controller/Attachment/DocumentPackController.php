<?php
/**
 *
 * 附件打包
 *
 * 资源库
 *
 * 附件
 * 20180301
 *
 */
class DocumentPackController extends Controller {
	
	
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
		$document_identity = $this->argument('documentId');
	}
}
?>