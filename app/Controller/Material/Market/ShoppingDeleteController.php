<?php
/**
 *
 * 购物车删除
 *
 * 营销
 *
 */
class ShoppingDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'shoppingId'=>array('type'=>'digital','tooltip'=>'购物车ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$shoppingId = $this->argument('shoppingId');
		
		
		$shoppingCount = $this->service('MarketShopping')->getShoppingIdCount($shoppingId);
		if(!$shoppingId){
			$this->info('订购信息不存在',40001);
		}
		
		$this->service('MarketShopping')->remove($shoppingId);
		
	}
}
?>