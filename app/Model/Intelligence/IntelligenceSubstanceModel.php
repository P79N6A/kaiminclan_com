<?php
/**
 *
 * 文章
 *
 * 情报
 *
 */
class IntelligenceSubstanceModel extends Model
{
    protected $_name = 'intelligence_substance';
    protected $_primary = 'identity';
	
	protected $_database = 'intelligence';
	
	//类型【1产品，2餐品,3文章，4评论，5用户，6商户】
	const INTELLIGENCE_SUBSTANCE_IDTYPE_GOOD = 1;
	
	const INTELLIGENCE_SUBSTANCE_IDTYPE_FOOD = 2;	
	
	const INTELLIGENCE_SUBSTANCE_IDTYPE_ARTICLE = 3;	
	
	const INTELLIGENCE_SUBSTANCE_IDTYPE_COMMENT = 4;	
	
	const INTELLIGENCE_SUBSTANCE_IDTYPE_USER = 5;	
	
	const INTELLIGENCE_SUBSTANCE_IDTYPE_BUSINESS = 6;	
	
	//状态【0:启用，1:禁用】
	const INTELLIGENCE_SUBSTANCE_STATUS_ENABLE = 0;
	
	const INTELLIGENCE_SUBSTANCE_STATUS_DISABLED = 1;	
	
	/**
	 * 获取收藏数据类型
	 *
	 * @return array
	 */
	public static function getIdtypeIds(){
		return array(
			self::INTELLIGENCE_SUBSTANCE_IDTYPE_ARTICLE,
			self::INTELLIGENCE_SUBSTANCE_IDTYPE_BUSINESS,
			self::INTELLIGENCE_SUBSTANCE_IDTYPE_COMMENT,
			self::INTELLIGENCE_SUBSTANCE_IDTYPE_FOOD,
			self::INTELLIGENCE_SUBSTANCE_IDTYPE_GOOD,
			self::INTELLIGENCE_SUBSTANCE_IDTYPE_USER
		);
	}
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::INTELLIGENCE_SUBSTANCE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::INTELLIGENCE_SUBSTANCE_STATUS_DISABLED,'label'=>'禁用'),
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
