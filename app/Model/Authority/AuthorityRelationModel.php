<?php
/**
 *
 * 关联
 *
 * 账户与权限
 
 
 				第三方平台

微信公众号	微信小程序	腾讯QQ	新浪微博

					账户
					
	客户	医生	供应商	管理员	运营
	
					粉丝
 *
 */
class AuthorityRelationModel extends Model
{
    protected $_name = 'authority_relation';
    protected $_primary = 'identity';

    //账户类型
	const AUTHORITY_RELATION_PLATFORM_WEIBO = 1;
	const AUTHORITY_RELATION_PLATFORM_WEIQQ = 2;
	const AUTHORITY_RELATION_PLATFORM_WEIXIN = 3;
	const AUTHORITY_RELATION_PLATFORM_WECHAT = 4;
	
	//状态【0:关注，1:取消】
	const AUTHORITY_RELATION_STATUS_FOLLOW = 0;
	
	const AUTHORITY_RELATION_STATUS_CANNEL_FOLLOW = 1;
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getRelationList(){
		return array(
			array('value'=>self::AUTHORITY_RELATION_PLATFORM_WEIBO,'label'=>'新浪微博'),
			array('value'=>self::AUTHORITY_RELATION_PLATFORM_WEIQQ,'label'=>'腾讯QQ'),
			array('value'=>self::AUTHORITY_RELATION_PLATFORM_WEIXIN,'label'=>'微信公众号'),
			array('value'=>self::AUTHORITY_RELATION_PLATFORM_WECHAT,'label'=>'微信小程序'),
		);
	}
	
	/**
	 * 获取店铺注册来源名称
	 *
	 * @param $relation 店铺状态
	 *
	 * @return string
	 */
	public static function getRelationTitle($relation){
		$relationTitle = '';
		$relationData = self::getRelationList();
		foreach($relationData as $key=>$data){
			if($data['value'] == $relation){
				$relationTitle = $data['label'];
				break;
			}
		}
		
		return $relationTitle;
	}
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_RELATION_STATUS_FOLLOW,'label'=>'关注'),
			array('value'=>self::AUTHORITY_RELATION_STATUS_CANNEL_FOLLOW,'label'=>'取消关注'),
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
