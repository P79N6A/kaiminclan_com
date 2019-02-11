<?php
/**
 *
 * 目录编辑
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'目录ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'隶属目录','default'=>0),
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
			'catalogue_identity'=>$this->argument('catalogue_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($catalogueId){
			$this->service('KnowledgeCatalogue')->update($setarr,$catalogueId);
		}else{
			
			$this->service('KnowledgeCatalogue')->insert($setarr);
		}
	}
}
?>