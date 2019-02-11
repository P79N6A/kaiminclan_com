<?php
/**
 *
 * 库存
 *
 * 头寸
 *
 */
class PositionInventoryModel extends Model
{
    protected $_name = 'position_inventory';
    protected $_primary = 'identity';
	
	protected $_database = 'trade';
	
	//类型【1产品，2餐品,3文章，4评论，5用户，6商户】
	const AUTHORITY_COLLECTION_IDTYPE_GOOD = 1;
	
	const AUTHORITY_COLLECTION_IDTYPE_FOOD = 2;	
	
	const AUTHORITY_COLLECTION_IDTYPE_ARTICLE = 3;	
	
	const AUTHORITY_COLLECTION_IDTYPE_COMMENT = 4;	
	
	const AUTHORITY_COLLECTION_IDTYPE_USER = 5;	
	
	const AUTHORITY_COLLECTION_IDTYPE_BUSINESS = 6;	
	
	//状态【0:启用，1:禁用】
	const AUTHORITY_COLLECTION_STATUS_ENABLE = 0;
	
	const AUTHORITY_COLLECTION_STATUS_DISABLED = 1;	
	
	/**
	 * 获取收藏数据类型
	 *
	 * @return array
	 */
	public static function getIdtypeIds(){
		return array(
			self::AUTHORITY_COLLECTION_IDTYPE_ARTICLE,
			self::AUTHORITY_COLLECTION_IDTYPE_BUSINESS,
			self::AUTHORITY_COLLECTION_IDTYPE_COMMENT,
			self::AUTHORITY_COLLECTION_IDTYPE_FOOD,
			self::AUTHORITY_COLLECTION_IDTYPE_GOOD,
			self::AUTHORITY_COLLECTION_IDTYPE_USER
		);
	}
	
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
