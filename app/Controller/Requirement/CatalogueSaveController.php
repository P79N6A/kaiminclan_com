<?php
/**
 *
 * 分类编辑
 *
 * 20180301
 *
 */
class CatalogueSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'分类ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
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
			'remark' => $this->argument('remark'),
		);
		
		$this->model('RequirementCatalogue')->start();
		
		if($catalogueId){
			$this->service('RequirementCatalogue')->update($setarr,$catalogueId);
		}else{
			if($this->service('RequirementCatalogue')->checkCatalogueTitle($setarr['title'])){
				$this->info('此分类已存在',400001);
			}
			$catalogueId = $this->service('RequirementCatalogue')->insert($setarr);
		}
		$this->model('RequirementCatalogue')->commit();
		
		$this->assign('catalogueId',$catalogueId);
	}
}
?>