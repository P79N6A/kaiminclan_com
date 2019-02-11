<?php
/**
 *
 * 概念编辑
 *
 * 20180301
 *
 */
class ConceptSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'conceptId'=>array('type'=>'digital','tooltip'=>'概念ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$conceptId = $this->argument('conceptId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($conceptId){
			$this->service('SecuritiesConcept')->update($setarr,$conceptId);
		}else{
			
			if($this->service('SecuritiesConcept')->checkCatalogTitle($setarr['title'])){
				
				$this->info('概念已存在',4001);
			}
			
			$this->service('SecuritiesConcept')->insert($setarr);
		}
	}
}
?>