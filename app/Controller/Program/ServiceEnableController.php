<?php
/**
 *
 * 服务启用
 *
 * 20180301
 *
 */
class ServiceEnableController extends Controller {
	
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
		
		$groupInfo = $this->service('ProgramService')->getServiceInfo($serviceId);
		if(!$groupInfo){
			$this->info('服务不存在',4101);
		}
		
		if($groupInfo['status'] == ProgramServiceModel::PAGINATION_ITEM_STATUS_DISABLED){
			$this->service('ProgramService')->update(array('status'=>ProgramServiceModel::PAGINATION_ITEM_STATUS_ENABLE),$serviceId);
		}
		
		
	}
}
?>