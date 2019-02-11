<?php
/**
 *
 * 栏目编辑
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'栏目ID','default'=>0),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'隶属栏目','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'编码','length'=>80),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'图片','default'=>0),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
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
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'code' => $this->argument('code'),
			'attachment_identity' => json_encode($this->argument('attachment_identity')),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark')
		);
		
		if($catalogueId){
			$this->service('IntelligenceCatalogue')->update($setarr,$catalogueId);
		}else{
			
			if($this->service('IntelligenceCatalogue')->checkCatalogueTitle($setarr['title'],$setarr['catalogue_identity'])){
				
				$this->info('栏目已存在',4001);
			}
			
			$this->service('IntelligenceCatalogue')->insert($setarr);
		}
	}
}
?>