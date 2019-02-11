<?php
/**
 *
 * 删除服务
 *
 * 20180301
 *
 */
class ServiceDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'serviceId'=>array('type'=>'digital','tooltip'=>'服务ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$serviceId = $this->argument('serviceId');
		
		$serviceList = $this->service('ProgramService')->getServiceInfo($serviceId);
		
		if(!$serviceList){
			$this->info('服务不存在',4101);
		}
		
		$this->service('ProgramService')->removeServiceId($serviceId);
		
		
	}
}
?>