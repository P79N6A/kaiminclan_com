<?php
class AuthorityResourcesModel extends Model{
	protected $_name = 'authority_resources';
	protected $_primary = 'identity';
	
	//'完成','待处理','草稿','开发'
	const AUTHORITY_RESOURCES_STATUS_ENABLE = 0;
	const AUTHORITY_RESOURCES_STATUS_DRAFT = 1;
	const AUTHORITY_RESOURCES_STATUS_DEVELOP = 2;
	const AUTHORITY_RESOURCES_STATUS_ACCEPT = 3;
	
	const AUTHORITY_RESOURCES_AUTHORITY_TYPE_USER = 1;
	const AUTHORITY_RESOURCES_AUTHORITY_TYPE_ROLE = 2;
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::AUTHORITY_RESOURCES_STATUS_FINISH,'label'=>'正常'),
			array('value'=>self::AUTHORITY_RESOURCES_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::AUTHORITY_RESOURCES_STATUS_DEVELOP,'label'=>'处理中'),
			array('value'=>self::AUTHORITY_RESOURCES_STATUS_ACCEPT,'label'=>'验收中'),
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
	
	public static function getResoucesTypeCode($type){
		$code = '';
		$fashionData = array(
			array('id'=>1,'label'=>'页面','code'=>'page'),
			array('id'=>2,'label'=>'操作','code'=>'action'),
		);
		foreach($fashionData as $key=>$data){
			if($data['id'] == $type){
				$code = $data['code'];
				break;
			}
		}
		
		return $code;
	}
}