<?php
/**
 *
 * 行业编辑
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
			'catalogId'=>array('type'=>'digital','tooltip'=>'行业ID','default'=>0),
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
			$this->service('InvestmentCatalog')->update($setarr,$catalogId);
		}else{
			
			if($this->service('InvestmentCatalog')->checkCatalogTitle($setarr['title'])){
				
				$this->info('行业已存在',4001);
			}
			
			$this->service('InvestmentCatalog')->insert($setarr);
		}
	}
}
?>