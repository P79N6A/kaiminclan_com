<?php
/**
 *
 * 类型
 *
 * 促销
 *
 */
class InteractionCommentModel extends Model
{
    protected $_name = 'interaction_comment';
    protected $_primary = 'identity';
	
	
	//状态【0开启，1关闭】	
	const INTERACTION_COMMENT_REPLY_ENABLE = 0;
	const INTERACTION_COMMENT_REPLY_DISABLE = 1;
	
	//状态【0正常，1禁用】	
	const INTERACTION_COMMENT_STATUS_ENABLE = 0;
	const INTERACTION_COMMENT_STATUS_DISABLE = 1;
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::INTERACTION_COMMENT_STATUS_ENABLE,'label'=>'启用'),
			array('value'=>self::INTERACTION_COMMENT_STATUS_DISABLE,'label'=>'禁用'),
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
