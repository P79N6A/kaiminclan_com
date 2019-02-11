<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class PaginationDomainModel extends Model
{
    protected $_name = 'pagination_domain';
    protected $_primary = 'identity';
	
	protected $_database = 'base';
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const PAGINATION_BLOCK_STATUS_ENABLE = 0;
	
	const PAGINATION_BLOCK_STATUS_DRAFT = 1;
	
	const PAGINATION_BLOCK_STATUS_DISABLED = 2;	
	
	const PAGINATION_BLOCK_STATUS_REMOVED = 3;
	
	const PAGINATION_BLOCK_STATUS_WAIT_EXAMINE = 4;
	
	/**
	 * 获取状态列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::PAGINATION_BLOCK_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::PAGINATION_BLOCK_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::PAGINATION_BLOCK_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::PAGINATION_BLOCK_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::PAGINATION_BLOCK_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
		);
	}
	
	/**
	 * 获取页面状态
	 *
	 * @param $status 页面状态
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
