<?php
/**
 *
 * 开仓
 *
 * 头寸
 *
 */
class PositionPurchaseModel extends Model
{
    protected $_name = 'position_purchase';
    protected $_primary = 'identity';
	
	protected $_database = 'trade';
	
	//类型【1证券，2外汇,3债券，4大宗商品】
	const POSITION_PURCHASE_IDTYPE_STOCK = 1;
	
	const POSITION_PURCHASE_IDTYPE_FOREX = 2;	
	
	const POSITION_PURCHASE_IDTYPE_BOND = 3;	
	
	const POSITION_PURCHASE_IDTYPE_FUTURES = 4;	
	
	//状态【0:启用，1:禁用】
	const POSITION_PURCHASE_STATUS_CLOSED = 0;
	
	const POSITION_PURCHASE_STATUS_WAIT = 1;	
	
	/**
	 * 获取收藏数据类型
	 *
	 * @return array
	 */
	public static function getIdtypeIds(){
		return array(
			self::POSITION_PURCHASE_IDTYPE_ARTICLE,
			self::POSITION_PURCHASE_IDTYPE_BUSINESS,
			self::POSITION_PURCHASE_IDTYPE_COMMENT,
			self::POSITION_PURCHASE_IDTYPE_FOOD,
			self::POSITION_PURCHASE_IDTYPE_GOOD,
			self::POSITION_PURCHASE_IDTYPE_USER
		);
	}
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::POSITION_PURCHASE_STATUS_CLOSED,'label'=>'已平仓'),
			array('value'=>self::POSITION_PURCHASE_STATUS_WAIT,'label'=>'待平仓'),
		);
	}
	
	/**
	 * 获取店铺注册来源名称
	 *
	 * @param $status 店铺状态
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

	public static  function getStyleTitle($style){
	    $style = intval($style);
	    $styleData = array('未定义','多单','空单');
	    return $styleData[$style];
    }
	
}
