<?php
/**
 *
 * 权限
 *
 * 权益
 *
 */
class InviolablePermissionModel extends Model
{
    protected $_name = 'inviolable_permission';
	
	protected $_database = 'trade';
	
	const INVIOLABLE_PERMISSION_IDTYPE_INDUSTRY = 1;
	const INVIOLABLE_PERMISSION_IDTYPE_COLUMN = 2;
	const INVIOLABLE_PERMISSION_IDTYPE_SYMBOL = 3;
	
	//状态【0:启用，1:禁用】
	const INVIOLABLE_SYMBOL_STATUS_ENABLE = 0;
	
	const INVIOLABLE_SYMBOL_STATUS_DISABLED = 1;	
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::INVIOLABLE_SYMBOL_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::INVIOLABLE_SYMBOL_STATUS_DISABLED,'label'=>'禁用'),
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
