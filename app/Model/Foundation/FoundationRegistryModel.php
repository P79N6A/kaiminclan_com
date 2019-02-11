<?php
/**
 *
 * 注册表
 *
 * 公共
 *
 */
class FoundationRegistryModel extends Model
{
    protected $_name = 'foundation_registry';
    protected $_primary = 'identity';
	
	
	//状态【0完成，1处理中,2待审核，3已拒绝】	
	const FOUNDATION_REGISTRY_STATUS_FINISH = 0;
	const FOUNDATION_REGISTRY_STATUS_AGREE = 1;
	const FOUNDATION_REGISTRY_STATUS_WAIT_EXAMINE = 2;
	const FOUNDATION_REGISTRY_STATUS_REFUSE_ = 3;
	
	/**
	 * 获取售后状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::FOUNDATION_REGISTRY_STATUS_FINISH,'label'=>'完成'),
			array('value'=>self::FOUNDATION_REGISTRY_STATUS_AGREE,'label'=>'同意'),
			array('value'=>self::FOUNDATION_REGISTRY_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
			array('value'=>self::FOUNDATION_REGISTRY_STATUS_REFUSE_Refund,'label'=>'已拒绝'),
		);
	}
	
	/**
	 * 获取售后状态名称
	 *
	 * @param $status 地址状态
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
