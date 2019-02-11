<?php
/**
 *
 * 栏目编辑
 *
 * 20180301
 *
 */
class CatalogSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'栏目ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($catalogId){
			$this->service('MaterialCatalog')->update($setarr,$catalogId);
		}else{
			
			if($this->service('MaterialCatalog')->checkCatalogTitle($setarr['title'])){
				
				$this->info('栏目已存在',4001);
			}
			
			$this->service('MaterialCatalog')->insert($setarr);
		}
	}
}
?>