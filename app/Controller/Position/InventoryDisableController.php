<?php
/**
 *
 * 禁用库存
 *
 * 20180301
 *
 */
class InventoryDisableController extends Controller {
	
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
		
		$inventoryId = $this->argument('inventoryId');
		
		$groupInfo = $this->service('PositionInventory')->getCatalogInfo($inventoryId);
		if(!$groupInfo){
			$this->info('库存不存在',4101);
		}
		
		if($groupInfo['status'] == PositionInventoryModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('PositionInventory')->update(array('status'=>PositionInventoryModel::PAGINATION_BLOCK_STATUS_DISABLED),$inventoryId);
		}
	}
}
?>