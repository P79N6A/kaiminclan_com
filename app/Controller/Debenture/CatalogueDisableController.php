<?php
/**
 *
 * 禁用分类
 *
 * 20180301
 *
 */
class CatalogueDisableController extends Controller {
	
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
		
		$groupInfo = $this->service('DebentureCatalogue')->getCatalogueInfo($catalogueId);
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		
		if($groupInfo['status'] == DebentureCatalogueModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('DebentureCatalogue')->update(array('status'=>DebentureCatalogueModel::PAGINATION_BLOCK_STATUS_DISABLED),$catalogueId);
		}
	}
}
?>