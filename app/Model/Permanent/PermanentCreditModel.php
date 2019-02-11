<?php
/**
 *
 * 分类
 *
 * 融资
 *
 */
class PermanentCreditModel extends Model
{
    protected $_name = 'borrow_credit';
    protected $_primary = 'identity';
	
	protected $_database = 'finance';
	//计息方式【0年，1季，2月，3周，4日】
	const PERMANENT_CREDIT_STYLE_YEAR = 0;
	const PERMANENT_CREDIT_STYLE_SEASON = 1;
	const PERMANENT_CREDIT_STYLE_MONTH = 2;
	const PERMANENT_CREDIT_STYLE_WEEK = 3;
	const PERMANENT_CREDIT_STYLE_DAILY = 4;
	
	
	//状态【0完成，1处理中,2待审核，3已拒绝】	
	const PROMOTION_BUSINESS_STATUS_ENABLE = 0;
	const PROMOTION_BUSINESS_STATUS_DISABLE = 1;
	
	/**
	 * 获取计息列表
	 *
	 * @return array
	 */
	public static function getStyleList(){
		return array(
			array('value'=>self::PERMANENT_CREDIT_STYLE_YEAR,'label'=>'年'),
			array('value'=>self::PERMANENT_CREDIT_STYLE_SEASON,'label'=>'季'),
			array('value'=>self::PERMANENT_CREDIT_STYLE_MONTH,'label'=>'月'),
			array('value'=>self::PERMANENT_CREDIT_STYLE_WEEK,'label'=>'周'),
			array('value'=>self::PERMANENT_CREDIT_STYLE_DAILY,'label'=>'日'),
		);
	}
	
	/**
	 * 获取计息名称
	 *
	 * @param $style 类型状态
	 *
	 * @return string
	 */
	public static function getStyleTitle($style){
		$styleTitle = '';
		$styleData = self::getStyleList();
		foreach($styleData as $key=>$data){
			if($data['value'] == $style){
				$styleTitle = $data['label'];
				break;
			}
		}
		
		return $styleTitle;
	}
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::PROMOTION_BUSINESS_STATUS_ENABLE,'label'=>'启用'),
			array('value'=>self::PROMOTION_BUSINESS_STATUS_DISABLE,'label'=>'禁用'),
		);
	}
	
	/**
	 * 获取类型状态名称
	 *
	 * @param $status 类型状态
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
