<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class ResourcesBlockModel extends Model
{
    protected $_name = 'resources_block';
    protected $_primary = 'identity';
	
	protected $_database = 'resources';
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const RESOURCES_BLOCK_STATUS_ENABLE = 0;
	
	const RESOURCES_BLOCK_STATUS_DRAFT = 1;
	
	const RESOURCES_BLOCK_STATUS_DISABLED = 2;	
	
	const RESOURCES_BLOCK_STATUS_REMOVED = 3;
	
	const RESOURCES_BLOCK_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取状态列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::RESOURCES_BLOCK_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::RESOURCES_BLOCK_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::RESOURCES_BLOCK_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::RESOURCES_BLOCK_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::RESOURCES_BLOCK_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
		);
	}
	
	/**
	 * 获取页面状态
	 *
	 * @param $status 页面状态
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
