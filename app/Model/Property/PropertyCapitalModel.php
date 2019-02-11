<?php
/**
 *
 * 账户
 *
 * 账户
 *
 */
class PropertyCapitalModel extends Model
{
    protected $_name = 'property_capital';
    protected $_primary = 'identity';
	
	protected $_database = 'trade';
	
	//状态【0:启用,1禁用,2草稿，3待审核,4拒绝，5删除】
	const PROPERTY_CAPITAL_STATUS_ENABLE = 0;
	
	const PROPERTY_CAPITAL_STATUS_DISABLED = 1;	
	
	const PROPERTY_CAPITAL_STATUS_DRAFT = 2;	
	
	const PROPERTY_CAPITAL_STATUS_WATI_VERIFY = 3;	
	
	const PROPERTY_CAPITAL_STATUS_REFUSE = 4;	
	const PROPERTY_CAPITAL_STATUS_REMOVE = 5;	
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::PROPERTY_CAPITAL_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::PROPERTY_CAPITAL_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::PROPERTY_CAPITAL_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::PROPERTY_CAPITAL_STATUS_WATI_VERIFY,'label'=>'待审核'),
			array('value'=>self::PROPERTY_CAPITAL_STATUS_REFUSE,'label'=>'审核拒绝'),
			array('value'=>self::PROPERTY_CAPITAL_STATUS_REMOVE,'label'=>'已删除'),
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
