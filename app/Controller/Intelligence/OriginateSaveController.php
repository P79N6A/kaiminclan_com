<?php
/**
 *
 * 合作伙伴编辑
 *
 * 20180301
 *
 */
class OriginateSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'originateId'=>array('type'=>'digital','tooltip'=>'合作伙伴ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
			'summary'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
			'fromurl'=>array('type'=>'url','tooltip'=>'来源URL','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$originateId = $this->argument('originateId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'summary' => $this->argument('summary'),
			'fromurl' => $this->argument('fromurl'),
		);
		
		$this->model('IntelligenceOriginate')->start();
		
		if($originateId){
			$this->service('IntelligenceOriginate')->update($setarr,$originateId);
		}else{
			
			
			$originateId = $this->service('IntelligenceOriginate')->insert($setarr);
		}
		$this->model('IntelligenceOriginate')->commit();
	}
}
?>