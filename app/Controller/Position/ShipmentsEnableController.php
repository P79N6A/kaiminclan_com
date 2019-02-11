<?php
/**
 *
 * 平仓启用
 *
 * 20180301
 *
 */
class ShipmentsEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'shipmentsId'=>array('type'=>'digital','tooltip'=>'平仓ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$shipmentsId = $this->argument('shipmentsId');
		
		$groupInfo = $this->service('PositionShipments')->getCatalogInfo($shipmentsId);
		if(!$groupInfo){
			$this->info('平仓不存在',4101);
		}
		
		if($groupInfo['status'] == PositionShipmentsModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('PositionShipments')->update(array('status'=>PositionShipmentsModel::PAGINATION_BLOCK_STATUS_ENABLE),$shipmentsId);
		}
		
		
	}
}
?>