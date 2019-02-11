<?php
/**
 *
 * 目录
 *
 * 知识库
 *
 */
class KnowledgeCatalogueModel extends Model
{
    protected $_name = 'knowledge_catalogue';
    protected $_primary = 'identity';
	
	protected $_database = 'intelligence';
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const KNOWLEDGE_CATALOGUE_STATUS_ENABLE = 0;
	
	const KNOWLEDGE_CATALOGUE_STATUS_DRAFT = 1;
	
	const KNOWLEDGE_CATALOGUE_STATUS_DISABLED = 2;	
	
	const KNOWLEDGE_CATALOGUE_STATUS_REMOVED = 3;
	
	const KNOWLEDGE_CATALOGUE_STATUS_WAIT_EXAMINE = 4;
	
	
	/**
	 * 获取角色状态
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::KNOWLEDGE_CATALOGUE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::KNOWLEDGE_CATALOGUE_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::KNOWLEDGE_CATALOGUE_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::KNOWLEDGE_CATALOGUE_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::KNOWLEDGE_CATALOGUE_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
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
