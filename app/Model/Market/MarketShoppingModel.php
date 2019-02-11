<?php
/**
 *
 * 购物车
 *
 * 销售
 *
 */
class MarketShoppingModel extends Model
{
    protected $_name = 'market_shopping';
    protected $_primary = 'identity';
	
	protected $_database = 'market';
	
	//产品类型
	//实物
	const MARKET_SHOPPING_IDTYPE_GOOD  = 0;
	//虚拟
	const MARKET_SHOPPING_IDTYPE_INVENTED = 1;
	
	//状态0购物车，1订单
	
	const MARKET_SHOPPING_STATUS_SHOPPING = 0;
	//订单
	const MARKET_SHOPPING_STATUS_ORDERDD = 2;
	//待付款
	const MARKET_SHOPPING_STATUS_CHECKOUT = 1;
	
	/**
	 * 获取订单状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::MARKET_SHOPPING_STATUS_SHOPPING,'label'=>'购物车'),
			array('value'=>self::MARKET_SHOPPING_STATUS_ORDERDD,'label'=>'订单'),
			array('value'=>self::MARKET_SHOPPING_STATUS_CHECKOUT,'label'=>'结账'),
		);
	}
	
	/**
	 * 获取订单状态名称
	 *
	 * @param $status 订单状态
	 *
	 * @return string
	 */
	public static function getStatusTitle($status){
		$statusTitle = '';
		$statusData = self::getStatusList();
		foreach($statusData as $key=>$data){
			if($data['value'] == $status){
				$statusTitle = $data['label'];
				break;
			}
		}
		
		return $statusTitle;
	}
}
