<?php
/**
 * 附件
 *
 * 资源库
 *
 */
class AttachmentDocumentModel extends Model
{
    protected $_name = 'attachment_document';
    protected $_primary = 'identity';
	
	//状态【0:启用，1:锁定
	const RESOURCES_ATTACHMENT_STATUS_ENABLE = 0;
	
	const RESOURCES_ATTACHMENT_STATUS_LOCKED = 1;
	
	const RESOURCES_ATTACHMENT_STATUS_REMOVED = 2;
	
	const RESOURCES_ATTACHMENT_STATUS_WAIT_EXAMINE = 3;
	
	/**
	 * 获取附件状态
	 *
	 * @return array
	 */
	public function getStatusList(){
		return array(
			array('value'=>self::RESOURCES_ATTACHMENT_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::RESOURCES_ATTACHMENT_STATUS_LOCKED,'label'=>'已锁定'),
			array('value'=>self::RESOURCES_ATTACHMENT_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
			array('value'=>self::RESOURCES_ATTACHMENT_STATUS_WAIT_REMOVED,'label'=>'删除'),
		);
	}
	
	/**
	 * 获取附件名称
	 *
	 * @param $status 店铺状态
	 *
	 * @return string
	 */
	public function getStatusTitle($status){
		$statusTitle = '';
		$statusData = $this->getStatusList();
		foreach($statusData as $key=>$data){
			if($data['value'] == $status){
				$statusTitle = $data['label'];
				break;
			}
		}
		
		return $statusTitle;
	}
}
