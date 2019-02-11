<?php
/**
 *
 * 调整订购数量
 *
 * 营销
 *
 */
class ShoppingAdjustQuantityController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'shoppingId'=>array('type'=>'digital','tooltip'=>'购物车ID'),
			'quantity'=>array('type'=>'digital','tooltip'=>'数量','default'=>1),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$shoppingId = $this->argument('shoppingId');
		$quantity = $this->argument('quantity');
		
		$shoppingCount = $this->service('MarketShopping')->getShoppingIdCount($shoppingId);
		if(!$shoppingCount){
			$this->info('订购信息不存在',40001);
		}
		
		$this->service('MarketShopping')->adjustShoppingQuantity($shoppingId,$quantity);
	}
}
?>