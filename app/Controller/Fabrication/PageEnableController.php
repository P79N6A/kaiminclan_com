<?php
/**
 *
 * 页面启用
 *
 * 20180301
 *
 */
class PageEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'pageId'=>array('type'=>'digital','tooltip'=>'页面ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$pageId = $this->argument('pageId');
		
		$groupInfo = $this->service('FabricationPage')->getPageInfo($pageId);
		if(!$groupInfo){
			$this->info('页面不存在',4101);
		}
		
		if($groupInfo['status'] == FabricationPageModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FabricationPage')->update(array('status'=>FabricationPageModel::PAGINATION_BLOCK_STATUS_ENABLE),$pageId);
		}
		
		
	}
}
?>