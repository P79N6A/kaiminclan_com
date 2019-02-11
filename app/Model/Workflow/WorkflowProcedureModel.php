<?php
/**
 *
 * 步骤
 *
 * 业务流
 *
 */
class WorkflowProcedureModel extends Model
{
    protected $_name = 'workflow_procedure';
    protected $_primary = 'identity';
	
	//用户类型
	const WORKFLOW_PROCEDURE_USER_TYPE_ROLE = 1;
	const WORKFLOW_PROCEDURE_USER_TYPE_USER = 2;
	
	
	
	//状态【完成，待处理，处理中】
	const WORKFLOW_PROCEDURE_STATUS_FINISH = 0;
	
	const WORKFLOW_PROCEDURE_STATUS_WAIT_HANDLE = 1;
	
	const WORKFLOW_PROCEDURE_STATUS_HANDLE = 2;	
	
	//类型【】
	const WORKFLOW_PROCEDURE_STYLE_START = 1;
	
	const WORKFLOW_PROCEDURE_STYLE_TASK = 2;
	
	const WORKFLOW_PROCEDURE_STYLE_AUTO = 3;
	
	const WORKFLOW_PROCEDURE_STYLE_END = 4;	
	
	
	
	/**
	 * 状态列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::WORKFLOW_PROCEDURE_STATUS_FINISH,'label'=>'完成'),
			array('value'=>self::WORKFLOW_PROCEDURE_STATUS_WAIT_HANDLE,'label'=>'待处理'),
			array('value'=>self::WORKFLOW_PROCEDURE_STATUS_HANDLE,'label'=>'处理中'),
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
