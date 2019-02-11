<?php
/**
 *
 * 结算
 *
 * 销售
 *
 */
class MarketSettlementModel extends Model
{
    protected $_name = 'market_settlement';
    protected $_primary = 'identity';
	
	protected $_database = 'market';
	
	
	//状态0启用，1禁用
	
	const MARKET_SETTLEMENT_STATUS_ENABLED = 0;
	const MARKET_SETTLEMENT_STATUS_DISABLED = 1;
	
	/**
	 * 获取结算状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::MARKET_SETTLEMENT_STATUS_ENABLED,'label'=>'启用'),
			array('value'=>self::MARKET_SETTLEMENT_STATUS_DISABLED,'label'=>'禁用'),
		);
	}
	
	/**
	 * 获取结算状态名称
	 *
	 * @param $status 结算状态
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
