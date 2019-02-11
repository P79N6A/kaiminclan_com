<?php
/**
 * 服务器
 *
 * 资源库
 *
 */
class AttachmentServerModel extends Model
{
    protected $_name = 'attachment_server';
    protected $_primary = 'identity';
	
	//状态【0:启用，1:锁定
	const ATTACHMENT_SERVER_STATUS_ENABLE = 0;
	
	const ATTACHMENT_SERVER_STATUS_LOCKED = 1;
	
	const ATTACHMENT_SERVER_STATUS_REMOVED = 2;
	
	const ATTACHMENT_SERVER_STATUS_WAIT_EXAMINE = 3;
	
	/**
	 * 获取附件状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::ATTACHMENT_SERVER_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::ATTACHMENT_SERVER_STATUS_LOCKED,'label'=>'已锁定'),
			array('value'=>self::ATTACHMENT_SERVER_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
			array('value'=>self::ATTACHMENT_SERVER_STATUS_WAIT_REMOVED,'label'=>'删除'),
		);
	}
	
	/**
	 * 获取附件名称
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
