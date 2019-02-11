<?php
/**
 *
 * 平台
 *
 * 系统
 *
 */
class FoundationPlatformModel extends Model
{
    protected $_name = 'foundation_platform';
    protected $_primary = 'identity';
	
	//类型【1产品，2餐品,3文章，4评论，5用户，6商户】
	const FOUNDATION_PLATFORM_PERMISSION_GOOD = 1;
	
	const FOUNDATION_PLATFORM_PERMISSION_FOOD = 2;	
	
	const FOUNDATION_PLATFORM_PERMISSION_ARTICLE = 3;	
	
	const FOUNDATION_PLATFORM_PERMISSION_COMMENT = 4;	
	
	const FOUNDATION_PLATFORM_PERMISSION_USER = 5;	
	
	const FOUNDATION_PLATFORM_PERMISSION_BUSINESS = 6;	
	
	
	//状态【0:启用，1:禁用】
	const AUTHORITY_FOLLOW_STATUS_ENABLE = 0;
	
	const AUTHORITY_FOLLOW_STATUS_DISABLED = 1;	
	
	/**
	 * 获取数据类型
	 *
	 * @return array
	 */
	public static function getIdtypeIds(){
		return array(
			self::FOUNDATION_PLATFORM_PERMISSION_ARTICLE,
			self::FOUNDATION_PLATFORM_PERMISSION_BUSINESS,
			self::FOUNDATION_PLATFORM_PERMISSION_COMMENT,
			self::FOUNDATION_PLATFORM_PERMISSION_FOOD,
			self::FOUNDATION_PLATFORM_PERMISSION_GOOD,
			self::FOUNDATION_PLATFORM_PERMISSION_USER
		);
	}
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_FOLLOW_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::AUTHORITY_FOLLOW_STATUS_DISABLED,'label'=>'禁用'),
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
