<?php
/**
 *
 * 需求编辑
 *
 * 20180301
 *
 */
class DemandSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'demandId'=>array('type'=>'digital','tooltip'=>'需求ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'subject_identity'=>array('type'=>'doc','tooltip'=>'项目','default'=>0),
			'platform_identity'=>array('type'=>'doc','tooltip'=>'平台','default'=>0),
			'idtype'=>array('type'=>'digital','tooltip'=>'需求类型','default'=>0),
			'id'=>array('type'=>'digital','tooltip'=>'类型ID','default'=>0),
			'address'=>array('type'=>'url','tooltip'=>'地址','default'=>''),
			'attachment_identity'=>array('type'=>'doc','tooltip'=>'图片','default'=>0),
			'content'=>array('type'=>'doc','tooltip'=>'需求介绍'),
			'prototype'=>array('type'=>'url','tooltip'=>'需求原型','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$demandId = $this->argument('demandId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'idtype' => $this->argument('idtype'),
			'id' => $this->argument('id'),
			'address' => $this->argument('address'),
			'subject_identity' => $this->argument('subject_identity'),
			'platform_identity' => $this->argument('platform_identity'),
			'attachment_identity' => json_encode($this->argument('attachment_identity')),
			'prototype' => $this->argument('prototype'),
			'remark' => $this->argument('remark')
		);
		
		if($demandId){
			$this->service('ProductionDemand')->update($setarr,$demandId);
		}else{
			
			if($this->service('ProductionDemand')->checkDemandTitle($setarr['title'])){
				
				$this->info('需求已存在',4001);
			}
			
			$this->service('ProductionDemand')->insert($setarr);
		}
	}
}
?>