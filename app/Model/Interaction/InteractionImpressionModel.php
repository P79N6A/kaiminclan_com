<?php
/**
 *
 * 印象
 *
 * 互动
 *
 */
class InteractionImpressionModel extends Model
{
    protected $_name = 'interaction_impression';
    protected $_primary = 'identity';
	
	//状态【0正常，1禁用】	
	const INTERACTION_IMPRESSION_STATUS_ENABLE = 0;
	const INTERACTION_IMPRESSION_STATUS_DISABLE = 1;
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::INTERACTION_IMPRESSION_STATUS_ENABLE,'label'=>'启用'),
			array('value'=>self::INTERACTION_IMPRESSION_STATUS_DISABLE,'label'=>'禁用'),
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
