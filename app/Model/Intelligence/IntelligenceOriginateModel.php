<?php
/**
 *
 * 合作伙伴
 *
 * 新闻
 *
 */
class IntelligenceOriginateModel extends Model
{
    protected $_name = 'intelligence_originate';
    protected $_primary = 'identity';
	
	protected $_database = 'intelligence';
	
	//默认角色
	//运维人员
	const AUTHORITY_ROLE_SUPER = 1;
	//运营人员
	const AUTHORITY_ROLE_ADMIN = 2;
	//供应商
	const AUTHORITY_ROLE_SUPPLIER = 3;
	//客户
	const AUTHORITY_ROLE_USER = 4;
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const AUTHORITY_ROLE_STATUS_ENABLE = 0;
	
	const AUTHORITY_ROLE_STATUS_DRAFT = 1;
	
	const AUTHORITY_ROLE_STATUS_DISABLED = 2;	
	
	const AUTHORITY_ROLE_STATUS_REMOVED = 3;
	
	const AUTHORITY_ROLE_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取角色类型
	 *
	 * @return array
	 */
	public static function getRoleTypeList(){
		return array(
			self::AUTHORITY_ROLE_SUPER,self::AUTHORITY_ROLE_ADMIN,self::AUTHORITY_ROLE_SUPPLIER,self::AUTHORITY_ROLE_USER
		);
	}
	
	/**
	 * 获取角色状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_ROLE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::AUTHORITY_ROLE_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::AUTHORITY_ROLE_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::AUTHORITY_ROLE_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::AUTHORITY_ROLE_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
		);
	}
	
	/**
	 * 获取角色状态名称
	 *
	 * @param $status 角色状态
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
