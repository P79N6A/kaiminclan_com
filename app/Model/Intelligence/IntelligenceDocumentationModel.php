<?php
/**
 *
 * 文章
 *
 * 新闻
 *
 */
class IntelligenceDocumentationModel extends Model
{
    protected $_name = 'intelligence_documentation';
    protected $_primary = 'identity';
	
	protected $_database = 'intelligence';
	
	//默认角色
	//运维人员
	const INTELLIGENCE_DOCUMENTATION_SUPER = 1;
	//运营人员
	const INTELLIGENCE_DOCUMENTATION_ADMIN = 2;
	//供应商
	const INTELLIGENCE_DOCUMENTATION_SUPPLIER = 3;
	//客户
	const INTELLIGENCE_DOCUMENTATION_USER = 4;
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const INTELLIGENCE_DOCUMENTATION_STATUS_ENABLE = 0;
	
	const INTELLIGENCE_DOCUMENTATION_STATUS_DRAFT = 1;
	
	const INTELLIGENCE_DOCUMENTATION_STATUS_DISABLED = 2;	
	
	const INTELLIGENCE_DOCUMENTATION_STATUS_REMOVED = 3;
	
	const INTELLIGENCE_DOCUMENTATION_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取角色类型
	 *
	 * @return array
	 */
	public static function getRoleTypeList(){
		return array(
			self::INTELLIGENCE_DOCUMENTATION_SUPER,self::INTELLIGENCE_DOCUMENTATION_ADMIN,self::INTELLIGENCE_DOCUMENTATION_SUPPLIER,self::INTELLIGENCE_DOCUMENTATION_USER
		);
	}
	
	/**
	 * 获取角色状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::INTELLIGENCE_DOCUMENTATION_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::INTELLIGENCE_DOCUMENTATION_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::INTELLIGENCE_DOCUMENTATION_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::INTELLIGENCE_DOCUMENTATION_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::INTELLIGENCE_DOCUMENTATION_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
