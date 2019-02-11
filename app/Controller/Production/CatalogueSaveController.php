<?php
/**
 *
 * 业务编辑
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'业务ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'subject_identity'=>array('type'=>'doc','tooltip'=>'项目','default'=>0),
			'platform_identity'=>array('type'=>'doc','tooltip'=>'平台','default'=>0),
			'content'=>array('type'=>'doc','tooltip'=>'情况说明'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'业务架构','default'=>0),
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
			'subject_identity' => $this->argument('subject_identity'),
			'platform_identity' => $this->argument('platform_identity'),
			'content' => $this->argument('content'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($catalogueId){
			$this->service('ProductionCatalogue')->update($setarr,$catalogueId);
		}else{
			
			if($this->service('ProductionCatalogue')->checkCatalogueTitle($setarr['title'])){
				
				$this->info('业务已存在',4001);
			}
			
			$this->service('ProductionCatalogue')->insert($setarr);
		}
	}
}
?>