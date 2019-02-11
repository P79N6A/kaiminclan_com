<?php
/**
 *
 * 用例类型编辑
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'用例类型ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($catalogueId){
			$this->service('FaultinessCatalogue')->update($setarr,$catalogueId);
		}else{
			
			if($this->service('FaultinessCatalogue')->checkCatalogueTitle($setarr['title'])){
				
				$this->info('用例类型已存在',4001);
			}
			
			$this->service('FaultinessCatalogue')->insert($setarr);
		}
	}
}
?>