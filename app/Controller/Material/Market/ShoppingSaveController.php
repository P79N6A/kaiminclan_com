<?php
/**
 *
 * 购物车编辑
 *
 * 营销
 *
 */
class ShoppingSaveController extends Controller {
	
	/** 权限 */
	protected $permission = 'user';
	/** 访问方式 */
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'id'=>array('type'=>'digital','tooltip'=>'订购ID'),
			'business_identity'=>array('type'=>'digital','tooltip'=>'店铺ID','default'=>0),
			'idtype'=>array('type'=>'string','tooltip'=>'订购类型'),
			'property'=>array('type'=>'digital','tooltip'=>'订购规格','default'=>''),
			'univalent'=>array('type'=>'money','tooltip'=>'单价'),
			'quantity'=>array('type'=>'digital','tooltip'=>'数量'),
			'mode'=>array('type'=>'digital','tooltip'=>'立即购买','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$id = $this->argument('id');
		$idtype = $this->argument('idtype');
		$property = json_encode($this->argument('property'));
		$univalent = $this->argument('univalent');
		$quantity = $this->argument('quantity');
		$business_identity = $this->argument('business_identity');
		$mode = $this->argument('mode');
		
		$shoppingCode = $this->service('MarketShopping')->getShoppingCode($id,$idtype,$property);
		
		$shoppingData = $this->service('MarketShopping')->getShoppingByCodeInfo($shoppingCode);
		if(!$shoppingData){
			
			$shoppingData = array(
				'business_identity'=>$business_identity,
				'id'=>$id,
				'code'=>$shoppingCode,
				'idtype'=>$idtype,
				'property'=>$property,
				'univalent'=>$univalent,
				'quantity'=>$quantity,
				'amount'=>$univalent*$quantity,
			);
			if($mode){
				$shoppingData['status'] = MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT;
			}
			
			$shoppingId = $this->service('MarketShopping')->insert($shoppingData);
		}else{
			$shoppingId = $shoppingData['identity'];
			$this->service('MarketShopping')->adjustShoppingQuantity($shoppingData['identity'],$quantity);
		}
		
		$this->assign('shoppingId',$shoppingId);
	}
}
?>