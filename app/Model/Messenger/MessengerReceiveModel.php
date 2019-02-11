<?php
/**
 *
 * 模板
 *
 * 信使
 *
 */
class MessengerReceiveModel extends Model
{
    protected $_name = 'messenger_receive';
    protected $_primary = 'identity';
	
	protected $_database = 'messenger';
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const MESSENGER_RECEIVE_STATUS_ENABLE = 0;
	
	const MESSENGER_RECEIVE_STATUS_DRAFT = 1;
	
	const MESSENGER_RECEIVE_STATUS_DISABLED = 2;	
	
	const MESSENGER_RECEIVE_STATUS_REMOVED = 3;
	
	const MESSENGER_RECEIVE_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取模板列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::MESSENGER_RECEIVE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::MESSENGER_RECEIVE_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::MESSENGER_RECEIVE_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::MESSENGER_RECEIVE_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::MESSENGER_RECEIVE_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
		);
	}
	
	/**
	 * 获取模板状态
	 *
	 * @param $status 模板状态
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
