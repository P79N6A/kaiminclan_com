<?php
/**
 *
 * 禁用栏目
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'栏目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('IntelligenceCatalogue')->getCatalogInfo($catalogueId);
		if(!$groupInfo){
			$this->info('栏目不存在',4101);
		}
		
		if($groupInfo['status'] == IntelligenceCatalogueModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('IntelligenceCatalogue')->update(array('status'=>IntelligenceCatalogueModel::PAGINATION_BLOCK_STATUS_DISABLED),$catalogueId);
		}
	}
}
?>