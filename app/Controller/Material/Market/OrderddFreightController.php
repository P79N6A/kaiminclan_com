<?php
/**
 *
 * 订单运费
 *
 * 流程
 *
 * 检测商品
 *
 * 营销
 *
 */
class OrderddFreightController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'districtId'=>array('type'=>'digital','tooltip'=>'地区ID','default'=>'')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$districtId = $this->argument('districtId');
		
		$provinceDistrictId = $this->service('FoundationDistrict')->getDistrictLevelId($districtId,
								FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_PROVINCE);
		if(!$provinceDistrictId){
			$provinceDistrictId = $districtId;
		}
		
		$shoppingCheckoutList = $this->service('MarketShopping')->getShoppingCheckoutData();
		
		if($shoppingCheckoutList){
			$productInfo = array();
			foreach($shoppingCheckoutList as $businessId=>$shoppingList){
				foreach($shoppingList as $cnt=>$shopping){
					$productInfo[$businessId][$shopping['id']]=$shopping['quantity'];
				}
			}
			
			$freightData = $freightList = array();
			$freightList = $this->service('GoodsDelivery')->getBusinessGoodsFreight($provinceDistrictId,$productInfo);
			if($freightList){
				foreach($freightList as $businessId=>$freight){
					$freightData[] = array(
						'amount' => $freight,
						'business_identity' => $businessId
					);
				}
			}
		}
		
		
		$this->assign('freright',$freightData);
	}
}
?>