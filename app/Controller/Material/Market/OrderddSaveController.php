<?php
/**
 *
 * 订单提交
 *
 * 流程
 *
 * 检测商品
 *
 * 营销
 *
 */
class OrderddSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'tesetimonialId'=>array('type'=>'digital','tooltip'=>'优惠卷','default'=>0),
			'distribution'=>array('type'=>'digital','tooltip'=>'送货方式'),
			'contactId'=>array('type'=>'digital','tooltip'=>'送货地址','default'=>0),
			'settlementId'=>array('type'=>'digital','tooltip'=>'支付方式'),
			'remark'=>array('type'=>'digital','tooltip'=>'备注-留言','default'=>''),
			'balance'=>array('type'=>'money','tooltip'=>'余额','default'=>0),
			'combi'=>array('type'=>'money','tooltip'=>'康贝','default'=>0),
			'paymentType'=>array('type'=>'digital','tooltip'=>'支付类型','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$tesetimonialIds = $this->argument('tesetimonialId');
		$settlement_identity = $this->argument('settlementId');
		$remark = $this->argument('remark');
		$distribution = $this->argument('distribution');
		$contact_identity = $this->argument('contactId');
		$paymentType = $this->argument('paymentType');
		
		if($contact_identity < 1 && $distribution == MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS){
			$this->info('没有设定收货地址',2003);
		}
		
		$checkountShoppingList = $this->service('MarketShopping')->getShoppingCheckoutData();
		if(!$checkountShoppingList){
			$this->info('没有可结账的商品',40011);
		}
		
		$provinceDistrictId = 0;
		if($distribution == MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS){
			$contactData = $this->service('MarketContact')->getContactInfo($contact_identity);
			if(!$contactData){
				$this->info('地址不存在',40012);
			}
			
			$provinceDistrictId = $this->service('FoundationDistrict')->getDistrictLevelId($contactData['district_identity'],
									FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_PROVINCE);
			if(!$provinceDistrictId){
				$provinceDistrictId = 1;
			}
			
		}
		
		
		$cnt = 1;
		$orderddCodeIds = array();
		foreach($checkountShoppingList as $businessId=>$shoppingList){
			
			//总数
			$orderddQuantity = 0;
			//总额
			$orderddAmount = 0;
			
			//折扣总额
			$discount_amount = 0;
						
			//优惠总额
			$coupon_amount = 0;
			//补贴金额
			$subsidy_amount = 0;
			$orderddFreight = 0;
				
			$couponIds = array();
			$freightProductIds = array();
			foreach($shoppingList as $key=>$shopping){
			
				$shoppingIds[] = $shopping['identity'];
			
				$orderddQuantity += $shopping['quantity'];
				$orderddAmount += $shopping['amount'];
				
				//检测是否定义优惠卷
				if(isset($flexibleId[$businessId])){
					$couponIds[] = $freight;
				}
				
				//实物商品计算运费
				if($shopping['idtype'] == MarketShoppingModel::MARKET_SHOPPING_IDTYPE_GOOD){
					$freightProductIds[$businessId][] = array($shopping['id']=>$shopping['quantity']);
				}
			}
			
			//运费
			if($distribution == MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS){
				$orderddFreight = $this->service('GoodsDelivery')->getBusinessGoodsFreight($provinceDistrictId,$freightProductIds);
				if($orderddFreight === false){
					$this->info('商品中存在不可配送',40013);
				}
				$orderddFreight = $orderddFreight[$businessId];
			}
			//折扣金额
			$freight_amount = $discount_amount = 0;
			
			//对应折扣信息
			$discountData = $this->service('CustomerGrade')->getCustomerGradeDiscount(array(
				$businessId=>array('goods_price_sum'=>$orderddAmount,'goods_freight_sun'=>$freight)
			),$this->session('customer_identity'));
			
			if(isset($discountData[$businessId])){
				if(isset($discountData[$businessId]['goods_price_discount'])){
					$discount_amount = $discountData[$businessId]['goods_price_discount'];
				}
				
				//免运费
				if(isset($discountData[$businessId]['goods_freight_discount'])){
					$freight_amount = $discountData[$businessId]['goods_freight_discount'];
				}
			}
			//优惠金额
			if(count($tesetimonialIds) > 0){
				
				$testimonialCounponList = $this->service('PromotionTestimonial')->getProductCouponInfo($tesetimonialIds);
				if($testimonialCounponList){
					foreach($testimonialCounponList as $key=>$couponData){
						$couponData = $couponData['product'];
						$denomination = $couponData['denomination'];
						$subsidy = $couponData['subsidy'];
						if($orderddAmount >= $couponData['amount']){
							switch($coupon['classify']){
								case PROMOTION_COUPON_CLASSIFY_CASH: 
									//现金
									$coupon_amount += $orderddAmount-$denomination;
								break;
								case PROMOTION_COUPON_CLASSIFY_DISCOUNT: 
									//折扣 
									$coupon_amount += $orderddAmount*($denomination/100);
								break;
							}
							//补贴金额
							if($subsidy > 0){
								$subsidy_amount += $orderddAmount*($subsidy/100);
							}
						}
					}
				}
			}
			
			
			//应付总额 订单总额-折扣总额-优惠总额+运费
			$payable_amount = $orderddAmount-$discount_amount-$coupon_amount+$freight_amount;
			$receivablesAmount += $payable_amount;
			
			$where = array();
			
			$orderddData['business_identity'][] = $businessId;
			$code = $this->service('MarketOrderdd')->getOrderddCode($cnt);
			$orderddData['code'][] = $code;
			$orderddCodeIds[$code] = $shoppingIds;
			
			$orderddData['order_time'][] = $this->getTime();
			$orderddData['remark'][] = $remark;
			$orderddData['subsidy_amount'][] = $subsidy_amount;
			$orderddData['payable_amount'][] = $payable_amount;
			$orderddData['discount_amount'][] = $discount_amount;
			$orderddData['coupon_amount'][] = $coupon_amount;
			$orderddData['freight_amount'][] = $freight_amount;
			$orderddData['freight'][] = $orderddFreight;
			$orderddData['amount'][] = $orderddAmount;
			$orderddData['quantity'][] = $orderddQuantity;
			$orderddData['settlement_identity'][] = $settlement_identity;
			$orderddData['contact_identity'][] = $contact_identity;
			$orderddData['distribution'][] = $distribution;
			$orderddData['status'][] = MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_PAYMENT;
			$cnt++;
		
		}
		
		//扫码支付
		$paymentType = $this->argument('paymentType');
		
		$receivablesCode = $this->service('MarketReceivables')->getReceivablesCode();
		
		$receivablesData = array(
			'amount'=>$receivablesAmount,
			'mode'=>$paymentType,
			'code'=>$receivablesCode,
		);
		$receivablesId = $this->service('MarketReceivables')->insert($receivablesData);
		
		$orderddId = $this->service('MarketOrderdd')->insert($orderddData,$receivablesId);
		
		$where = array(
			'code'=>array_keys($orderddCodeIds)
		);
		
		$orderddList = $this->model('MarketOrderdd')->field('identity,code')->where($where)->select();
		if(!$orderddList || count($orderddCodeIds) != count($orderddList)){
			$this->info('订单提交失败',41001);
		}
		
		$orderddId = array();
		foreach($orderddList as $key=>$orderdd){
			$orderddId[$orderdd['code']] = $orderdd['identity'];
		}
		
		foreach($orderddCodeIds as $code=>$shoppingIds){
			$result = $this->service('MarketShopping')->update(array('orderdd_identity'=>$orderddId[$code],'status'=>MarketShoppingModel::MARKET_SHOPPING_STATUS_ORDERDD),$shoppingIds);
		}
		
		
		
		$this->assign('receivablesId',$receivablesId);
		
	}
}
?>