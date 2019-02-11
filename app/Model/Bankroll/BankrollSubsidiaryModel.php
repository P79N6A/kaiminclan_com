<?php
/**
 *
 * 收藏
 *
 * 权限
 *
 */
class BankrollSubsidiaryModel extends Model
{
    protected $_name = 'bankroll_subsidiary';
    protected $_primary = 'identity';
	
	protected $_database = 'trade';
	
	//状态【0:启用，1:禁用】
	const AUTHORITY_COLLECTION_STATUS_ENABLE = 0;
	
	const AUTHORITY_COLLECTION_STATUS_DISABLED = 1;	
	
	//状态【0:启用，1:禁用】
	const BANKROLL_SUBSIDIARY_DIRECTION_REVENUE = 1;
	
	const BANKROLL_SUBSIDIARY_DIRECTION_EXPENSES = 2;	
	
	
	
	/**
	 * 获取状态
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
	 * 状态
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
	
	/**
	 * 获取类型
	 *
	 * @return array
	 */
	public static function getDirectionList(){
		return array(
			array('value'=>self::BANKROLL_SUBSIDIARY_DIRECTION_REVENUE,'label'=>'流入'),
			array('value'=>self::BANKROLL_SUBSIDIARY_DIRECTION_EXPENSES,'label'=>'流出'),
		);
	}
	
	/**
	 * 状态
	 *
	 * @param $direction 流水类型
	 *
	 * @return string
	 */
	public static function getDirectionTitle($direction){
		$directionTitle = '';
		$directionData = self::getDirectionList();
		foreach($directionData as $key=>$data){
			if($data['value'] == $direction){
				$directionTitle = $data['label'];
				break;
			}
		}
		
		return $directionTitle;
	}
	
}
