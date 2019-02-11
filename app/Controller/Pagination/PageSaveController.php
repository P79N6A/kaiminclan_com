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
			'domain_identity'=>array('type'=>'digital','tooltip'=>'域名'),
			'platform_identity'=>array('type'=>'digital','tooltip'=>'平台'),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'业务'),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'summary'=>array('type'=>'doc','tooltip'=>'介绍','length'=>80,'default'=>''),
			'folder'=>array('type'=>'letter','tooltip'=>'主机','length'=>80,'default'=>''),
			'role_identity'=>array('type'=>'digital','tooltip'=>'权限','length'=>80),
			'primaltplname'=>array('type'=>'string','tooltip'=>'模板文件','length'=>80),
			'url'=>array('type'=>'string','tooltip'=>'URL','length'=>80),
			'setting'=>array('type'=>'doc','tooltip'=>'参数','default'=>''),
			'seotitle'=>array('type'=>'string','tooltip'=>'SEO标题','length'=>80,'default'=>''),
			'seokeyword'=>array('type'=>'string','tooltip'=>'SEO关键字','length'=>80,'default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
			'seodescription'=>array('type'=>'string','tooltip'=>'SEO描述','length'=>80,'default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$pageId = $this->argument('pageId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'domain_identity' => $this->argument('domain_identity'),
			'platform_identity' => $this->argument('platform_identity'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'summary' => $this->argument('summary'),
			'folder' => $this->argument('folder'),
			'setting' => json_encode($this->argument('setting'),JSON_UNESCAPED_UNICODE),
			'role_identity' => $this->argument('role_identity'),
			'primaltplname' => $this->argument('primaltplname'),
			'url' => $this->argument('url'),
			'seotitle' => $this->argument('seotitle'),
			'seokeyword' => $this->argument('seokeyword'),
			'seodescription' => $this->argument('seodescription'),
			'remark' => $this->argument('remark')
		);
		
		
		if($pageId){
			$this->service('PaginationPage')->update($setarr,$pageId);
		}else{
			
			if($this->service('PaginationPage')->checkPageTitle($setarr['title'],$setarr['platform_identity'],$setarr['url'])){
				
				$this->info('页面已存在',4001);
			}
			$this->service('PaginationPage')->insert($setarr);
		}
	}
}
?>