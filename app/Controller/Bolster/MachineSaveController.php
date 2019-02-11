<?php
/**
 *
 * 机器编辑
 *
 * 20180301
 *
 */
class MachineSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'machineId'=>array('type'=>'digital','tooltip'=>'机器ID','default'=>0),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'项目','default'=>0),
			'platform_identity'=>array('type'=>'digital','tooltip'=>'平台'),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'host'=>array('type'=>'string','tooltip'=>'机器IP'),
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
		
		$machineId = $this->argument('machineId');
		
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
		
		if($machineId){
			$this->service('BolsterMachine')->update($setarr,$machineId);
		}else{
			
			if($this->service('BolsterMachine')->checkMachineTitle($setarr['title'])){
				
				$this->info('机器已存在',4001);
			}
			
			$this->service('BolsterMachine')->insert($setarr);
		}
	}
}
?>