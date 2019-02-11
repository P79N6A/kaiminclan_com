<?php
/**
 *
 * 订单折扣
 *
 * 流程
 *
 * 检测商品
 *
 * 营销
 *
 */
class OrderddDiscountController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'freight'=>array('type'=>'doc','tooltip'=>'运费信息')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$freight = $this->argument('freight');
		$frerghtFormatData = array();
		if($freight){
			foreach($freight as $key=>$data){
				$frerghtFormatData[$data['business_identity']] = $data['amount'];
			}
		}
		$shoppingCheckoutList = $this->service('MarketShopping')->getShoppingCheckoutData();
		
		if($shoppingCheckoutList){
			$productInfo = array();
			foreach($shoppingCheckoutList as $businessId=>$shoppingList){
				foreach($shoppingList as $cnt=>$shopping){
					$productInfo[$businessId]['goods_price_sum'] += $shopping['amount'];
					$productInfo[$businessId]['goods_freight_sun']=$frerghtFormatData[$businessId];
				}
			}
			$discountData = $discountList = array();
			//对应折扣信息
			$discountList = $this->service('CustomerBusinessGrade')->getCustomerGradeDiscount($productInfo,$this->session('customer_identity'));
			if($discountList){
				foreach($discountList as $businessId=>$discount){
					$discountTemp = array(
						'amount'=>12,
						'freight'=>0,
						'business_identity'=>$businessId
					);
					//产品优惠
					if($discount['goods_price_discount']){
						$discountTemp['amount'] = $discount['goods_price_discount'];
					}
					//免邮费
					if($discount['goods_freight_discount']){
						$discountTemp['freight'] = $discount['goods_freight_discount'];
					}
					$discountData[] = $discountTemp;
				}
			}
		
			
		}
		
		
			
		$this->assign('discount',$discountData);
	}
}
?>