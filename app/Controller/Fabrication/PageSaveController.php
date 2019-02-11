<?php
/**
 *
 * 页面编辑
 *
 * 20180301
 *
 */
class PageSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'pageId'=>array('type'=>'digital','tooltip'=>'页面ID','default'=>0),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'项目','default'=>0),
			'frontend_identity'=>array('type'=>'digital','tooltip'=>'页面'),
			'weight'=>array('type'=>'digital','tooltip'=>'页面分级'),
			'start_time'=>array('type'=>'date','tooltip'=>'开始时间','format'=>'dateline'),
			'stop_time'=>array('type'=>'date','tooltip'=>'结束时间','format'=>'dateline'),
			'content'=>array('type'=>'doc','tooltip'=>'开发介绍','default'=>''),
			'liability_subscriber_identity'=>array('type'=>'digital','tooltip'=>'责任人'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$pageId = $this->argument('pageId');
		
		$setarr = array(
			'subject_identity'=>$this->argument('subject_identity'),
			'weight'=>$this->argument('weight'),
			'start_time'=>$this->argument('start_time'),
			'stop_time'=>$this->argument('stop_time'),
			'frontend_identity'=>$this->argument('frontend_identity'),
			'content'=>$this->argument('content'),
			'liability_subscriber_identity'=>$this->argument('liability_subscriber_identity'),
			'remark'=>$this->argument('remark')
		);
		
		if($pageId){
			$this->service('FabricationPage')->update($setarr,$pageId);
		}else{
			
			if($this->service('FabricationPage')->checkPageTitle($setarr['title'])){
				
				$this->info('页面已存在',4001);
			}
			
			$this->service('FabricationPage')->insert($setarr);
		}
	}
}
?>