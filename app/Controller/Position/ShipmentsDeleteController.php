<?php
/**
 *
 * 删除平仓
 *
 * 20180301
 *
 */
class ShipmentsDeleteController extends Controller {
	
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
		
		$removeShipmentsIds = $this->argument('shipmentsId');
		
		$groupInfo = $this->service('PositionShipments')->getShipmentsInfo($removeShipmentsIds);
		
		if(!$groupInfo){
			$this->info('平仓不存在',4101);
		}
		
		$this->service('PositionShipments')->removeShipmentsId($removeShipmentsIds);
		
		$sourceTotal = count($shipmentsId);
		$successNum = count($removeShipmentsIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>