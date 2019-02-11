<?php
/**
 *
 * 敏感词
 *
 * 安全中心
 *
 */
class SecurityReductionModel extends Model
{
    protected $_name = 'security_reduction';
    protected $_primary = 'identity';
	
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const SECURITY_KEENNESS_STATUS_ENABLE = 0;
	
	const SECURITY_KEENNESS_STATUS_DRAFT = 1;
	
	const SECURITY_KEENNESS_STATUS_DISABLED = 2;	
	
	const SECURITY_KEENNESS_STATUS_REMOVED = 3;
	
	const SECURITY_KEENNESS_STATUS_WAIT_EXAMINE = 4;
	
	
	/**
	 * 获取角色状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::SECURITY_KEENNESS_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::SECURITY_KEENNESS_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::SECURITY_KEENNESS_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::SECURITY_KEENNESS_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::SECURITY_KEENNESS_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
