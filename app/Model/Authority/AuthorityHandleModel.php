<?php
/**
 *
 * 操作
 *
 * 权限
 *
 */
class GoodsBrandModel extends Model
{
    protected $_name = 'authority_handle';
    protected $_primary = 'identity';
	
	//用户
	const AUTHORITY_HANDLE_IDTYPE_USER = 0;
	//角色
	const AUTHORITY_HANDLE_IDTYPE_ROLE  = 1;
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const AUTHORITY_HANDLE_STATUS_ENABLE = 0;
	
	const AUTHORITY_HANDLE_STATUS_DRAFT = 1;
	
	const AUTHORITY_HANDLE_STATUS_DISABLED = 2;	
	
	const AUTHORITY_HANDLE_STATUS_REMOVED = 3;
	
	const AUTHORITY_HANDLE_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_HANDLE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::AUTHORITY_HANDLE_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::AUTHORITY_HANDLE_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::AUTHORITY_HANDLE_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::AUTHORITY_HANDLE_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
