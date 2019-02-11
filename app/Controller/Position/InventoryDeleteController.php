<?php
/**
 *
 * 删除库存
 *
 * 20180301
 *
 */
class InventoryDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'inventoryId'=>array('type'=>'digital','tooltip'=>'库存ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeInventoryIds = $this->argument('inventoryId');
		
		$groupInfo = $this->service('PositionInventory')->getInventoryInfo($removeInventoryIds);
		
		if(!$groupInfo){
			$this->info('库存不存在',4101);
		}
		
		$this->service('PositionInventory')->removeInventoryId($removeInventoryIds);
		
		$sourceTotal = count($inventoryId);
		$successNum = count($removeInventoryIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>