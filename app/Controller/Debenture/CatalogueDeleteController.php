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
		
		$catalogueInfo = $this->service('DebentureCatalogue')->getCatalogueInfo($catalogueId);
		
		if(!$catalogueInfo){
			$this->info('分类不存在',4101);
		}
		if(!is_array($catalogueueId)){
			$catalogueInfo = array($catalogueInfo);
		}
		
		$removeCatalogueIds = array();
		foreach($catalogueInfo as $key=>$catalogue){
				$removeCatalogueIds[] = $catalogue['identity'];
		}
		
		$this->service('DebentureCatalogue')->removeCatalogueId($removeCatalogueIds);
		
		$sourceTotal = count($catalogueueId);
		$successNum = count($removeCatalogueIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>