<?php
/**
 *
 * 禁用经纪
 *
 * 20180301
 *
 */
class BrokerDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'brokerId'=>array('type'=>'digital','tooltip'=>'经纪ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$brokerId = $this->argument('brokerId');
		
		$groupInfo = $this->service('IntercalateBroker')->getCatalogInfo($brokerId);
		if(!$groupInfo){
			$this->info('经纪不存在',4101);
		}
		
		if($groupInfo['status'] == IntercalateBrokerModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('IntercalateBroker')->update(array('status'=>IntercalateBrokerModel::PAGINATION_BLOCK_STATUS_DISABLED),$brokerId);
		}
	}
}
?>