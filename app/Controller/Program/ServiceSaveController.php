<?php
/**
 *
 * 服务编辑
 *
 * 20180301
 *
 */
class ServiceSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'serviceId'=>array('type'=>'digital','tooltip'=>'服务ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'隶属服务','default'=>0),
			'nickname'=>array('type'=>'string','tooltip'=>'隶属单位'),
			'summary'=>array('type'=>'string','tooltip'=>'职能'),
			'parameter'=>array('type'=>'string','tooltip'=>'职责'),
			'script'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'startup'=>array('type'=>'string','tooltip'=>'备注','length'=>200,'default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$serviceId = $this->argument('serviceId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'nickname' => $this->argument('nickname'),
			'summary' => $this->argument('summary'),
			'parameter' => $this->argument('parameter'),
			'script' => $this->argument('script'),
			'startup' => $this->argument('startup'),
		);
		
		if($serviceId){
			$this->service('ProgramService')->update($setarr,$serviceId);
		}else{
			
			
			$this->service('ProgramService')->insert($setarr);
		}
	}
}
?>