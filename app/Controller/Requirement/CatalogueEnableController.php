<?php
/**
 *
 * 分类启用
 *
 * 20180301
 *
 */
class CatalogueEnableController extends Controller {
	
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
		
		$catalogueInfo = $this->service('RequirementCatalogue')->getCatalogueInfo($catalogueId);
		if(!$catalogueInfo){
			$this->info('分类不存在',4101);
		}
		
		if($catalogueInfo['status'] == RequirementCatalogueModel::BILLBOARD_CATALOGUE_STATUS_DISABLED){
			$this->service('RequirementCatalogue')->update(array('status'=>RequirementCatalogueModel::BILLBOARD_CATALOGUE_STATUS_ENABLE),$catalogueId);
		}
		
		
	}
}
?>