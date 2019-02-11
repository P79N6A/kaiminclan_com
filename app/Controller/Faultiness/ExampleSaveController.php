<?php
/**
 *
 * 测试用例编辑
 *
 * 20180301
 *
 */
class ExampleSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'revenueId'=>array('type'=>'digital','tooltip'=>'测试用例ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'用例类型'),
			'channel_identity'=>array('type'=>'digital','tooltip'=>'测试类型'),
			'performance'=>array('type'=>'string','tooltip'=>'功能特性'),
			'facility_identity'=>array('type'=>'digital','tooltip'=>'测试工具'),
			'frontend'=>array('type'=>'string','tooltip'=>'前置条件'),
			'content'=>array('type'=>'string','tooltip'=>'用例介绍'),
			'weight'=>array('type'=>'digital','tooltip'=>'优先级'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$revenueId = $this->argument('revenueId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'channel_identity' => $this->argument('channel_identity'),
			'performance' => $this->argument('performance'),
			'facility_identity' => $this->argument('facility_identity'),
			'frontend' => $this->argument('frontend'),
			'content' => $this->argument('content'),
			'weight' => $this->argument('weight'),
			'remark' => $this->argument('remark'),
		);
		
		if($revenueId){
			$this->service('FaultinessExample')->update($setarr,$revenueId);
		}else{
			
			if($this->service('FaultinessExample')->checkExampleTitle($setarr['title'])){
				
				$this->info('测试用例已存在',4001);
			}
			
			$this->service('FaultinessExample')->insert($setarr);
		}
	}
}
?>