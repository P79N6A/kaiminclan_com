<?php
/**
 *
 * 维护编辑
 *
 * 20180301
 *
 */
class MaintainSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'maintainId'=>array('type'=>'digital','tooltip'=>'维护ID','default'=>0),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'项目','default'=>0),
			'platform_identity'=>array('type'=>'digital','tooltip'=>'平台'),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'host'=>array('type'=>'string','tooltip'=>'维护IP'),
			'username'=>array('type'=>'string','tooltip'=>'账户名称'),
			'password'=>array('type'=>'string','tooltip'=>'登录密码'),
			'start_time'=>array('type'=>'date','tooltip'=>'开始时间','format'=>'dateline','default'=>0),
			'stop_time'=>array('type'=>'date','tooltip'=>'结束时间','format'=>'dateline','default'=>0),
			'content'=>array('type'=>'doc','tooltip'=>'介绍','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$maintainId = $this->argument('maintainId');
		
		$setarr = array(
			'subject_identity'=>$this->argument('subject_identity'),
			'platform_identity'=>$this->argument('platform_identity'),
			'title'=>$this->argument('title'),
			'content'=>$this->argument('content'),
			'username'=>$this->argument('username'),
			'password'=>$this->argument('password'),
			'host'=>$this->argument('host'),
			'start_time'=>$this->argument('start_time'),
			'stop_time'=>$this->argument('stop_time'),
			'remark'=>$this->argument('remark')
		);
		
		if($maintainId){
			$this->service('BolsterMaintain')->update($setarr,$maintainId);
		}else{
			
			if($this->service('BolsterMaintain')->checkMaintainTitle($setarr['title'])){
				
				$this->info('维护已存在',4001);
			}
			
			$this->service('BolsterMaintain')->insert($setarr);
		}
	}
}
?>