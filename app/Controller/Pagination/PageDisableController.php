<?php
/**
 *
 * 禁用页面
 *
 * 20180301
 *
 */
class PageDisableController extends Controller {
	
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
		
		$groupInfo = $this->service('PaginationPage')->getPageInfo($pageId);
		if(!$groupInfo){
			$this->info('页面不存在',4101);
		}
		
		if($groupInfo['status'] == PaginationPageModel::PAGINATION_PAGE_STATUS_ENABLE){
			$this->service('PaginationPage')->update(array('status'=>PaginationPageModel::PAGINATION_PAGE_STATUS_DISABLED),$pageId);
		}
	}
}
?>