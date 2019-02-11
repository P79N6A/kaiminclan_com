<?php
/**
 *
 * 平台编辑
 *
 * 20180301
 *
 */
class PlatformSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'platformId'=>array('type'=>'digital','tooltip'=>'平台ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'所属项目','default'=>0),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'业务架构','default'=>0),
			'device_identity'=>array('type'=>'digital','tooltip'=>'设备','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$platformId = $this->argument('platformId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'subject_identity' => $this->argument('subject_identity'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'device_identity' => $this->argument('device_identity'),
			'remark' => $this->argument('remark'),
		);
		
		if($platformId){
			$this->service('ProductionPlatform')->update($setarr,$platformId);
		}else{
			
			if($this->service('ProductionPlatform')->checkPlatformTitle($setarr['title'])){
				
				$this->info('平台已存在',4001);
			}
			
			$this->service('ProductionPlatform')->insert($setarr);
		}
	}
}
?>