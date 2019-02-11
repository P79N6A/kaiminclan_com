<?php
/**
 *
 * 流程
 *
 * 业务流
 *
 */
class WorkflowProcessModel extends Model
{
    protected $_name = 'workflow_process';
    protected $_primary = 'identity';
		
	
	
	//状态【完成，待处理，处理中】
	const WORKFLOW_MISSION_STATUS_ENABLE = 0;
	
	const WORKFLOW_MISSION_STATUS_WAIT_HANDLE = 1;
	
	const WORKFLOW_MISSION_STATUS_HANDLE = 2;	
	
	
	/**
	 * 状态列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::WORKFLOW_MISSION_STATUS_FINISH,'label'=>'完成'),
			array('value'=>self::WORKFLOW_MISSION_STATUS_WAIT_HANDLE,'label'=>'待处理'),
			array('value'=>self::WORKFLOW_MISSION_STATUS_HANDLE,'label'=>'处理中'),
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
