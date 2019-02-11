<?php
/**
 *
 * 禁用服务
 *
 * 20180301
 *
 */
class ServiceDisableController extends Controller {
	
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
		
		if($groupInfo['status'] == ProgramServiceModel::PAGINATION_ITEM_STATUS_ENABLE){
			$this->service('ProgramService')->update(array('status'=>ProgramServiceModel::PAGINATION_ITEM_STATUS_DISABLED),$serviceId);
		}
	}
}
?>