<?php
/**
 *
 * 删除分类
 *
 * 20180301
 *
 */
class CatalogueDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$catalogueList = $this->service('RequirementCatalogue')->getCatalogueInfo($catalogueId);
		
		if(!$catalogueList){
			$this->info('分类不存在',4101);
		}
		
		$this->service('RequirementCatalogue')->removeCatalogueId($catalogueId);
		
		
	}
}
?>