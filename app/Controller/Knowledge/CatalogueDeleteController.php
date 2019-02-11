<?php
/**
 *
 * 删除目录
 *
 * 20180301
 *
 */
class CatalogueDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'目录ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeCatalogueIds = $this->argument('catalogueId');
		
		$catalogueInfo = $this->service('KnowledgeCatalogue')->getCatalogueInfo($removeCatalogueIds);
		
		
		if(!$catalogueInfo){
			$this->info('目录不存在',4101);
		}
		
		$this->service('KnowledgeCatalogue')->removeCatalogueId($removeCatalogueIds);
		
		$sourceTotal = count($catalogueId);
		$successNum = count($removeCatalogueIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>