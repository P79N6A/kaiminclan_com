<?php
/**
 *
 * 区域
 *
 * 公共
 *
 */
class FoundationDistrictModel extends Model
{
    protected $_name = 'foundation_district';
    protected $_primary = 'identity';
	
	
	//深度【级别【0洲，1地区，2国，3省，4市，5县/区，6镇，7乡/村】】	
	const FOUNDATION_DISTRICT_LEVEL_CONTINENT = 0;	
	const FOUNDATION_DISTRICT_LEVEL_REGION = 1;	
	const FOUNDATION_DISTRICT_LEVEL_COUNTRY = 2;	
	const FOUNDATION_DISTRICT_LEVEL_PROVINCE = 3;	
	const FOUNDATION_DISTRICT_LEVEL_CITY = 4;	
	const FOUNDATION_DISTRICT_LEVEL_COUNTY_DISTRICT = 5;	
	const FOUNDATION_DISTRICT_LEVEL_TOWN = 6;	
	const FOUNDATION_DISTRICT_LEVEL_TOWNSHIP_VILLAGE = 7;
	
	
	//状态【0启用，1禁用】	
	const FOUNDATION_DISTRICT_STATUS_ENABLE = 0;
	const FOUNDATION_DISTRICT_STATUS_DISABLE = 1;
	
	/**
	 * 获取售后状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::FOUNDATION_DISTRICT_STATUS_ENABLE,'label'=>'启用'),
			array('value'=>self::FOUNDATION_DISTRICT_STATUS_DISABLE,'label'=>'禁用'),
		);
	}
	
	/**
	 * 获取售后状态名称
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
