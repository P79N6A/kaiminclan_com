<?php
/**
 *
 * 账户
 *
 * 权限
 *
 */
class AuthoritySubscriberModel extends Model
{
    protected $_name = 'authority_subscriber';
    protected $_primary = 'identity';
	
	//账户类型
	const AUTHORITY_SUBSCRIBER_IDTYPE_SYSTEM = 0;
	
	const AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS = 1;
	
	const AUTHORITY_SUBSCRIBER_IDTYPE_CLIENT = 2;	
	
	const AUTHORITY_SUBSCRIBER_IDTYPE_EMPLOYEE = 3;
	
	const AUTHORITY_SUBSCRIBER_IDTYPE_TRADE_ACCOUNT = 4;
	
	//账户类型
	const AUTHORITY_SUBSCRIBER_PLATFORM_WEIBO = 1;
	const AUTHORITY_SUBSCRIBER_PLATFORM_WEIQQ = 2;
	const AUTHORITY_SUBSCRIBER_PLATFORM_WEIXIN = 3;
	const AUTHORITY_SUBSCRIBER_PLATFORM_WECHAT = 4;
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const AUTHORITY_SUBSCRIBER_STATUS_ENABLE = 0;
	
	const AUTHORITY_SUBSCRIBER_STATUS_DRAFT = 1;
	
	const AUTHORITY_SUBSCRIBER_STATUS_DISABLED = 2;	
	
	const AUTHORITY_SUBSCRIBER_STATUS_REMOVED = 3;
	
	const AUTHORITY_SUBSCRIBER_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_SUBSCRIBER_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::AUTHORITY_SUBSCRIBER_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::AUTHORITY_SUBSCRIBER_STATUS_DISABLED,'label'=>'锁定'),
			array('value'=>self::AUTHORITY_SUBSCRIBER_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::AUTHORITY_SUBSCRIBER_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
