<?php
/**
 *
 * 类型
 *
 * 渠道
 *
 */
class PassagewayChannelModel extends Model
{
    protected $_name = 'passageway_channel';
    protected $_primary = 'identity';
	
	protected $_database = 'market';
	
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const PASSAGEWAY_CHANNEL_STATUS_ENABLE = 0;
	
	const PASSAGEWAY_CHANNEL_STATUS_DRAFT = 1;
	
	const PASSAGEWAY_CHANNEL_STATUS_DISABLED = 2;	
	
	const PASSAGEWAY_CHANNEL_STATUS_REMOVED = 3;
	
	const PASSAGEWAY_CHANNEL_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::PASSAGEWAY_CHANNEL_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::PASSAGEWAY_CHANNEL_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::PASSAGEWAY_CHANNEL_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::PASSAGEWAY_CHANNEL_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::PASSAGEWAY_CHANNEL_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
