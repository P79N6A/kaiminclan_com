<?php
/**
 *
 * 订单
 *
 * 销售
 *
 */
class MarketOrderddModel extends Model
{
    protected $_name = 'market_orderdd';
    protected $_primary = 'identity';
	
	protected $_database = 'market';
	
	//评论，0未评论，1已评论
	const MARKET_ORDERDD_COMMENT_WAIT = 0;
	const MARKET_ORDERDD_COMMENT_FINISH = 1;
	
	//配送方式【0自提，1物流快递】
	const MARKET_ORDERDD_DISTRIBUTION_SINCE = 0;
	const MARKET_ORDERDD_DISTRIBUTION_LOGISTICS = 1;
	
	//状态0已完成，1已取消，2待付款，3待发货，4待收货，5已关闭，6已删除，7无效单，8拒绝单，9购物车
	const MARKET_ORDERDD_STATUS_FINISH = 0;
	
	const MARKET_ORDERDD_STATUS_CANNEL = 1;
	
	const MARKET_ORDERDD_STATUS_WAIT_PAYMENT = 2;	
	
	const MARKET_ORDERDD_STATUS_WAIT_DELIVERY = 3;
	
	const MARKET_ORDERDD_STATUS_WAIT_RECEIPT = 4;
	
	const MARKET_ORDERDD_STATUS_CLOSED = 5;
	
	const MARKET_ORDERDD_STATUS_REMOVE = 6;
	
	const MARKET_ORDERDD_STATUS_INVALID = 7;
	
	const MARKET_ORDERDD_STATUS_REFUSE = 8;
	
	const MARKET_ORDERDD_STATUS_SHOPPING = 9;
	
	/**
	 * 获取配送方式
	 *
	 * @return array
	 */
	public static function getDistributionList(){
		return array(
			array('value'=>self::MARKET_ORDERDD_DISTRIBUTION_SINCE,'label'=>'店内自提'),
			array('value'=>self::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS,'label'=>'物流快递'),
		);
	}
	
	/**
	 * 获取配送方式
	 *
	 * @param $status 配送方式
	 *
	 * @return string
	 */
	public static function getDistributionTitle($distribution){
		$statusTitle = '';
		$statusData = self::getDistributionList();
		foreach($statusData as $key=>$data){
			if($data['value'] == $status){
				$statusTitle = $data['label'];
				break;
			}
		}
		
		return $statusTitle;
	}
	
	/**
	 * 获取订单状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::MARKET_ORDERDD_STATUS_FINISH,'label'=>'已完成'),
			array('value'=>self::MARKET_ORDERDD_STATUS_CANNEL,'label'=>'已取消'),
			array('value'=>self::MARKET_ORDERDD_STATUS_WAIT_PAYMENT,'label'=>'待付款'),
			array('value'=>self::MARKET_ORDERDD_STATUS_WAIT_DELIVERY,'label'=>'待发货'),
			array('value'=>self::MARKET_ORDERDD_STATUS_WAIT_RECEIPT,'label'=>'待收货'),
			array('value'=>self::MARKET_ORDERDD_STATUS_CLOSED,'label'=>'已关闭'),
			array('value'=>self::MARKET_ORDERDD_STATUS_REMOVE,'label'=>'已删除'),
			array('value'=>self::MARKET_ORDERDD_STATUS_SHOPPING,'label'=>'购物车'),
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
