<?php
class AuthorityManageModel extends Model{
	protected $_name = 'authority_manage';
	protected $_primary = 'identity';
	
	//'完成','待处理','草稿','开发'
	const AUTHORITY_MANAGE_STATUS_ENABLE = 0;
	const AUTHORITY_MANAGE_STATUS_DRAFT = 1;
	const AUTHORITY_MANAGE_STATUS_DEVELOP = 2;
	const AUTHORITY_MANAGE_STATUS_ACCEPT = 3;
	
	const AUTHORITY_MANAGE_AUTHORITY_TYPE_USER = 1;
	const AUTHORITY_MANAGE_AUTHORITY_TYPE_ROLE = 2;
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_MANAGE_STATUS_FINISH,'label'=>'正常'),
			array('value'=>self::AUTHORITY_MANAGE_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::AUTHORITY_MANAGE_STATUS_DEVELOP,'label'=>'处理中'),
			array('value'=>self::AUTHORITY_MANAGE_STATUS_ACCEPT,'label'=>'验收中'),
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
	public static function getFashionCode($fashion){
		$code = '';
		$fashionData = array(
			array('id'=>1,'label'=>'账户','code'=>'uid'),
			array('id'=>2,'label'=>'指定账户','code'=>'uid'),
			array('id'=>3,'label'=>'指定角色','code'=>'role'),
			array('id'=>4,'label'=>'指定区域','code'=>'area'),
			array('id'=>5,'label'=>'本角色','code'=>'role'),
			array('id'=>6,'label'=>'本角色及以下','code'=>'role'),
			array('id'=>7,'label'=>'本区域','code'=>'area'),
			array('id'=>8,'label'=>'本区域及以下','code'=>'area'),
		);
		foreach($fashionData as $key=>$data){
			if($data['id'] == $fashion){
				$code = $data['code'];
				break;
			}
		}
		
		return $code;
	}
}