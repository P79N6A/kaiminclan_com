<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class RecruitmentCultivateModel extends Model
{
    protected $_name = 'recruitment_cultivate';
    protected $_primary = 'identity';
	
	protected $_database = 'human';
	
	
	//状态【0:启用，1:禁用】
	const RECRUITMENT_CULTIVATE_STATUS_ENABLE = 0;
	
	const RECRUITMENT_CULTIVATE_STATUS_DISABLED = 1;	
	
	
	/**
	 * 获取店铺状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::RECRUITMENT_CULTIVATE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::RECRUITMENT_CULTIVATE_STATUS_DISABLED,'label'=>'禁用'),
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
