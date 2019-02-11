<?php
/**
 *
 * 库存编辑
 *
 * 20180301
 *
 */
class InventorySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'inventoryId'=>array('type'=>'digital','tooltip'=>'库存ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'amount'=>array('type'=>'money','tooltip'=>'金额','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$inventoryId = $this->argument('inventoryId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'remark' => $this->argument('remark')
		);
		
		if($inventoryId){
			$this->service('PositionInventory')->update($setarr,$inventoryId);
		}else{
			
			$this->service('PositionInventory')->insert($setarr);
		}
	}
}
?>