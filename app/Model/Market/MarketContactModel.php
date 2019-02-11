<?php
/**
 *
 * 地址
 *
 * 销售
 *
 */
class MarketContactModel extends Model
{
    protected $_name = 'market_contact';
    protected $_primary = 'identity';
	
	protected $_database = 'market';
	
	
	
	//默认地址	0是，1否
	
	const MARKET_CONTACT_SELECTED_YES = 1;
	const MARKET_CONTACT_SELECTED_NO = 0;
	
	
	//状态0启用，1禁用
	
	const MARKET_CONTACT_STATUS_ENABLED = 0;
	const MARKET_CONTACT_STATUS_DISABLED = 1;
	
	/**
	 * 获取地址状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::MARKET_CONTACT_STATUS_ENABLED,'label'=>'启用'),
			array('value'=>self::MARKET_CONTACT_STATUS_DISABLED,'label'=>'禁用'),
		);
	}
	
	/**
	 * 获取地址状态名称
	 *
	 * @param $status 地址状态
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
