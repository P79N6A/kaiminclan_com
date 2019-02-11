<?php
/**
 *
 * 账户
 *
 * 账户
 *
 */
class PromotionStyleModel extends Model
{
    protected $_name = 'promotion_style';
    protected $_primary = 'identity';
	
	protected $_database = 'market';
	
	//状态【0:启用，1:禁用】
	const AUTHORITY_COLLECTION_STATUS_ENABLE = 0;
	
	const AUTHORITY_COLLECTION_STATUS_DISABLED = 1;	
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_COLLECTION_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::AUTHORITY_COLLECTION_STATUS_DISABLED,'label'=>'禁用'),
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
