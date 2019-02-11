<?php
/**
 *
 * 目录编辑
 *
 * 20180301
 *
 */
class CatalogSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'目录ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'目录名称','length'=>60),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'隶属目录','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'目录介绍','length'=>200,'default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'目录状态','default'=>ResourcesCatalogModel::RESOURCES_CATALOG_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$title = $this->argument('title');
		$catalogue_identity = $this->argument('catalogue_identity');
		$remark = $this->argument('remark');
		$status = $this->argument('status');
		
		if($catalogId){
			$this->service('ResourcesCatalog')->update(array('title'=>$title,'remark'=>$remark,'catalogue_identity'=>$catalogue_identity),$catalogId);
		}else{
			
			if($this->service('ResourcesCatalog')->checkTitle($title)){
				
				$this->info('目录已存在',4001);
			}
			
			$this->service('ResourcesCatalog')->insert($title,$remark,$catalogue_identity,$status);
		}
	}
}
?>