<?php
/**
 *
 * 页面
 *
 * 系统
 *
 */
class FoundationPaginationModel extends Model
{
    protected $_name = 'foundation_pagination';
    protected $_primary = 'identity';
	
	/*
		$permission = array('public','admin','user','supplier','client','guest');
	*/
	const FOUNDATION_PAGINATION_PERMISSION_PUBLIC = 0;
	
	const FOUNDATION_PAGINATION_PERMISSION_ADMIN = 1;	
	
	const FOUNDATION_PAGINATION_PERMISSION_USER = 2;	
	
	const FOUNDATION_PAGINATION_PERMISSION_SUPPLIER = 3;	
	
	const FOUNDATION_PAGINATION_PERMISSION_CLIENT = 4;	
	
	const FOUNDATION_PAGINATION_PERMISSION_GUEST = 5;	
	
	
	//状态【0:启用，1:禁用】
	const AUTHORITY_FOLLOW_STATUS_ENABLE = 0;
	
	const AUTHORITY_FOLLOW_STATUS_DISABLED = 1;	
	
	/**
	 * 获取数据类型
	 *
	 * @return array
	 */
	public static function getIdtypeIds(){
		return array(
			self::FOUNDATION_PAGINATION_PERMISSION_PUBLIC,
			self::FOUNDATION_PAGINATION_PERMISSION_ADMIN,
			self::FOUNDATION_PAGINATION_PERMISSION_USER,
			self::FOUNDATION_PAGINATION_PERMISSION_SUPPLIER,
			self::FOUNDATION_PAGINATION_PERMISSION_CLIENT,
			self::FOUNDATION_PAGINATION_PERMISSION_GUEST
		);
	}
	
	/**
	 * 获取权限
	 *
	 * @return array
	 */
	public static function getPermissionList(){
		return array(
			array('value'=>self::FOUNDATION_PAGINATION_PERMISSION_PUBLIC,'label'=>'PUBLIC'),
			array('value'=>self::FOUNDATION_PAGINATION_PERMISSION_ADMIN,'label'=>'ADMIN'),
			array('value'=>self::FOUNDATION_PAGINATION_PERMISSION_USER,'label'=>'USER'),
			array('value'=>self::FOUNDATION_PAGINATION_PERMISSION_SUPPLIER,'label'=>'SUPPLIER'),
			array('value'=>self::FOUNDATION_PAGINATION_PERMISSION_CLIENT,'label'=>'CILENT'),
			array('value'=>self::FOUNDATION_PAGINATION_PERMISSION_GUEST,'label'=>'GUEST'),
		);
	}
	
	/**
	 * 获取店铺注册来源名称
	 *
	 * @param $permission 店铺状态
	 *
	 * @return string
	 */
	public static function getPermissionTitle($permission){
		$permissionTitle = '';
		$permissionData = self::getPermissionList();
		foreach($permissionData as $key=>$data){
			if($data['value'] == $permission){
				$permissionTitle = $data['label'];
				break;
			}
		}
		
		return $permissionTitle;
	}
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_FOLLOW_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::AUTHORITY_FOLLOW_STATUS_DISABLED,'label'=>'禁用'),
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
