<?php
/**
 *
 * 产品
 *
 * 基金
 *
 */
class FundProductModel extends Model
{
    protected $_name = 'fund_product';
    protected $_primary = 'identity';
	
	protected $_database = 'fund';
	
	
	//状态【0完成，1处理中,2待审核，3已拒绝,4草稿】	
	const FUND_PRODUCT_STATUS_ENABLE = 0;
	const FUND_PRODUCT_STATUS_HAND = 1;
	const FUND_PRODUCT_STATUS_WAIT_EXAMINE = 2;
	const FUND_PRODUCT_STATUS_REFUSE = 3;
	const FUND_PRODUCT_STATUS_DRAFT = 4;
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::FUND_PRODUCT_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::FUND_PRODUCT_STATUS_HAND,'label'=>'处理中'),
			array('value'=>self::FUND_PRODUCT_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
			array('value'=>self::FUND_PRODUCT_STATUS_REFUSE,'label'=>'已拒绝'),
			array('value'=>self::FUND_PRODUCT_STATUS_DRAFT,'label'=>'草稿'),
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
