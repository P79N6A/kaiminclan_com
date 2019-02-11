<?php
/**
 *
 * 平仓
 *
 * 头寸
 *
 */
class PositionShipmentsModel extends Model
{
    protected $_name = 'position_shipment';
    protected $_primary = 'identity';
	
	protected $_database = 'trade';
	
	//类型【1证券，2外汇,3债券，4大宗商品】
	const POSITION_SHIPMENTS_IDTYPE_STOCK = 1;
	
	const POSITION_SHIPMENTS_IDTYPE_FOREX = 2;	
	
	const POSITION_SHIPMENTS_IDTYPE_BOND = 3;	
	
	const POSITION_SHIPMENTS_IDTYPE_FUTURES = 4;	
	
	//状态【0:启用，1:禁用】
	const POSITION_SHIPMENTS_STATUS_ENABLE = 0;
	
	const POSITION_SHIPMENTS_STATUS_DISABLED = 1;	
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::POSITION_SHIPMENTS_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::POSITION_SHIPMENTS_STATUS_DISABLED,'label'=>'禁用'),
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
	
}
