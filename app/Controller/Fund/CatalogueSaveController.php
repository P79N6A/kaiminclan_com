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
			$this->service('FundCatalogue')->update($setarr,$catalogueId);
		}else{
			
			if($this->service('FundCatalogue')->checkTitle($setarr['title'])){
				
				$this->info('分类已存在',4001);
			}
			
			$this->service('FundCatalogue')->insert($setarr);
		}
	}
}
?>