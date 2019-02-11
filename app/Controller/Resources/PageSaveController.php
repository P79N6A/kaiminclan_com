<?php
/**
 *
 * 页面编辑
 *
 * 20180301
 *
 */
class PageSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'pageId'=>array('type'=>'digital','tooltip'=>'页面ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'keyword'=>array('type'=>'string','tooltip'=>'关键字','length'=>80),
			'description'=>array('type'=>'string','tooltip'=>'描述','length'=>80),
			'cover_attachment_identity'=>array('type'=>'digital','tooltip'=>'封面图','default'=>0),
			'full_link'=>array('type'=>'url','tooltip'=>'链接地址'),
			'status'=>array('type'=>'digital','tooltip'=>'页面状态','default'=>PaginationPageModel::PAGINATION_PAGE_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$pageId = $this->argument('pageId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'keyword' => $this->argument('keyword'),
			'description' => $this->argument('description'),
			'cover_attachment_identity' => $this->argument('cover_attachment_identity'),
			'full_link' => $this->argument('full_link'),
			'status' => $this->argument('status')
		);
		
		if($pageId){
			$this->service('PaginationPage')->update($setarr,$pageId);
		}else{
			
			if($this->service('PaginationPage')->checkTitle($title)){
				
				$this->info('页面已存在',4001);
			}
			
			$this->service('PaginationPage')->insert($setarr);
		}
	}
}
?>