<?php
/**
 *
 * 工作
 *
 * 业务流
 *
 */
class WorkflowTroubleModel extends Model
{
    protected $_name = 'workflow_trouble';
    protected $_primary = 'identity';
		
	
	
	//状态【完成，草稿，处理中】
	const WORKFLOW_TROUBLE_STATUS_FINISH = 0;
	
	const WORKFLOW_TROUBLE_STATUS_DRAFT = 1;
	
	const WORKFLOW_TROUBLE_STATUS_HANDLE = 2;	
	
	
	/**
	 * 状态列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::WORKFLOW_TROUBLE_STATUS_FINISH,'label'=>'完成'),
			array('value'=>self::WORKFLOW_TROUBLE_STATUS_WAIT_HANDLE,'label'=>'待处理'),
			array('value'=>self::WORKFLOW_TROUBLE_STATUS_HANDLE,'label'=>'处理中'),
		);
	}
	
	/**
	 * 状态名称
	 *
	 * @param $status 状态
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
