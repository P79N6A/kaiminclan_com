<?php
/**
 *
 * 订购结账
 *  选择结账商品-检测数据准确-提取库存-确认
 * 营销
 *
 */
class ShoppingCheckoutController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'shoppingId'=>array('type'=>'digital','tooltip'=>'订购ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$shoppingId = $this->argument('shoppingId');
		
		$shoppingCount = $this->service('MarketShopping')->getShoppingIdCount($shoppingId);
		if(!$shoppingCount){
			$this->info('订购信息不存在',40001);
		}
		
		//满足结账商品数量，和预期结账商品数量不一致
		if($shoppingCount != count($shoppingId)){
			$this->info('选购的商品存在错误，请重新提交',40002);
		}
		
		
		$shoppingList = $this->service('MarketShopping')->getShoppingInfo($shoppingId);
		
		$shoppingGoodId = array();
		$lockedGoodInventory = array();
		foreach($shoppingList as $key=>$shopping){
			$shoppingGoodId[] = $shopping['id'];
			$lockedGoodInventory[$shopping['id']] = $shopping['quantity'];
		}
		
		//检测库存
		$isKeepInventory = true;
		$checkoutGoodsInventory = $this->service('Goods')->getGoodsStorageByIds($shoppingGoodId);
		if($checkoutGoodsInventory){
		
			foreach($checkoutGoodsInventory as $goodId=>$inventory){
				if($inventory < 1){
					$isKeepInventory = false;
					break;
				}
			}
		}
		
		//锁定库存
		$lockedResult = $this->service('Goods')->lockGoodsStorage($lockedGoodInventory);
		
		if(!$isKeepInventory || !$lockedResult){
			$this->info('库存不足',40003);
			
		}
		
		//商品重置
		$checkoutData = array(
			'status'=>MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT
		);
		
		$this->service('MarketShopping')->update($checkoutData,$shoppingId);
		
	}
}
?>