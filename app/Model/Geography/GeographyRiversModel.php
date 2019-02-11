<?php
/**
 *
 * 区域
 *
 * 地理
 *
 */
class GeographyRiversModel extends Model
{
    protected $_name = 'geography_rivers';
    protected $_primary = 'identity';
	
	protected $_database = 'intelligence'; 
	
	//状态【0:启用，1:禁用】
	const GEOGRAPHY_RIVERS_STATUS_ENABLE = 0;
	
	const GEOGRAPHY_RIVERS_STATUS_DISABLED = 1;	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::GEOGRAPHY_RIVERS_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::GEOGRAPHY_RIVERS_STATUS_DISABLED,'label'=>'禁用'),
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
