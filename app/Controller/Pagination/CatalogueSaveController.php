<?php
/**
 *
 * 目录编辑
 *
 * 20180301
 *
 */
class CatalogueSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'目录ID','default'=>0),
			'platform_identity'=>array('type'=>'digital','tooltip'=>'平台'),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'folder'=>array('type'=>'letter','tooltip'=>'主机','length'=>80),
			'status'=>array('type'=>'digital','tooltip'=>'状态'),
            'role_identity'=>array('type'=>'digital','tooltip'=>'权限','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'platform_identity' => $this->argument('platform_identity'),
            'role_identity' => $this->argument('role_identity'),
			'code' => $this->argument('folder'),
			'status' => $this->argument('status'),
			'remark' => $this->argument('remark')
		);
		
		
		if($catalogueId){
			$this->service('PaginationCatalogue')->update($setarr,$catalogueId);
		}else{
			
			if($this->service('PaginationCatalogue')->checkCatalogueTitle($setarr['title'],$setarr['platform_identity'])){
				
				$this->info('目录已存在',4001);
			}
			$this->service('FoundationCatalogue')->insert($setarr);
		}
	}
}
?>