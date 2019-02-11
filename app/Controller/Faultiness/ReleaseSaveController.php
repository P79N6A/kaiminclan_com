<?php
/**
 *
 * 测试用例编辑
 *
 * 20180301
 *
 */
class ReleaseSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'releaseId'=>array('type'=>'digital','tooltip'=>'测试用例ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'string','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$releaseId = $this->argument('releaseId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark'),
		);
		
		if($releaseId){
			$this->service('FaultinessRelease')->update($setarr,$releaseId);
		}else{
			
			if($this->service('FaultinessRelease')->checkReleaseTitle($setarr['title'])){
				
				$this->info('测试用例已存在',4001);
			}
			
			$this->service('FaultinessRelease')->insert($setarr);
		}
	}
}
?>