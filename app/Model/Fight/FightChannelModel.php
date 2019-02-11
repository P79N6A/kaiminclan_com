<?php
/**
 *
 * 分类
 *
 * 基金
 *
 */
class FightChannelModel extends Model
{
    protected $_name = 'fight_channel';
    protected $_primary = 'identity';
	
	protected $_database = 'trade';
	
	
	//状态【0完成，1处理中,2待审核，3已拒绝】	
	const FUND_CATALOGUE_STATUS_ENABLE = 0;
	const FUND_CATALOGUE_STATUS_DISABLE = 1;
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::FUND_CATALOGUE_STATUS_ENABLE,'label'=>'启用'),
			array('value'=>self::FUND_CATALOGUE_STATUS_DISABLE,'label'=>'禁用'),
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
