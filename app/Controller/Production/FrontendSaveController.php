<?php
/**
 *
 * 页面编辑
 *
 * 20180301
 *
 */
class FrontendSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'frontendId'=>array('type'=>'digital','tooltip'=>'页面ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'prototype'=>array('type'=>'url','tooltip'=>'页面原型'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$frontendId = $this->argument('frontendId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'prototype' => $this->argument('prototype'),
			'remark' => $this->argument('remark'),
		);
		
		if($frontendId){
			$this->service('ProductionFrontend')->update($setarr,$frontendId);
		}else{
			
			if($this->service('ProductionFrontend')->checkFrontendTitle($setarr['title'])){
				
				$this->info('页面已存在',4001);
			}
			
			$this->service('ProductionFrontend')->insert($setarr);
		}
	}
}
?>