<?php
/**
 *
 * 类型
 *
 * 财务
 *
 */
class MechanismTypologicalModel extends Model
{
    protected $_name = 'mechanism_typological';
    protected $_primary = 'identity';
	
	protected $_database = 'finance';
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const MECHANISM_BANKCARD_STATUS_ENABLE = 0;
	
	const MECHANISM_BANKCARD_STATUS_DRAFT = 1;
	
	const MECHANISM_BANKCARD_STATUS_DISABLED = 2;	
	
	const MECHANISM_BANKCARD_STATUS_REMOVED = 3;
	
	const MECHANISM_BANKCARD_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::MECHANISM_BANKCARD_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::MECHANISM_BANKCARD_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::MECHANISM_BANKCARD_STATUS_DISABLED,'label'=>'锁定'),
			array('value'=>self::MECHANISM_BANKCARD_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::MECHANISM_BANKCARD_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
