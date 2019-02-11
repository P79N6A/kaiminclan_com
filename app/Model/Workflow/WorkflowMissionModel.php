<?php
/**
 *
 * 任务
 *
 * 业务流
 *
 */
class WorkflowMissionModel extends Model
{
    protected $_name = 'workflow_mission';
    protected $_primary = 'identity';
	
	//结果【同意，退回（退回上一步，退回发起人）】
	const WORKFLOW_MISSION_FRUIT_AGREE = 1;
	const WORKFLOW_MISSION_FRUIT_PREV = 2;
	const WORKFLOW_MISSION_FRUIT_INITIATOR = 3;
	
	
	
	//状态【完成，待处理，处理中】
	const WORKFLOW_MISSION_STATUS_FINISH = 0;
	
	const WORKFLOW_MISSION_STATUS_WAIT_HANDLE = 1;
	
	const WORKFLOW_MISSION_STATUS_HANDLE = 2;	
	
	/**
	 * 意见列表
	 *
	 * @return array
	 */
	public static function getFruitList(){
		return array(
			array('value'=>self::WORKFLOW_MISSION_FRUIT_AGREE,'label'=>'同意'),
			array('value'=>self::WORKFLOW_MISSION_FRUIT_PREV,'label'=>'退回上一步'),
			array('value'=>self::WORKFLOW_MISSION_FRUIT_DESIGNATED,'label'=>'退回指定步骤'),
			array('value'=>self::WORKFLOW_MISSION_FRUIT_INITIATOR,'label'=>'退回发起人'),
		);
	}
	
	/**
	 * 意见名称
	 *
	 * @param $fruit 意见
	 *
	 * @return string
	 */
	public static function getFruitTitle($fruit){
		$fruitTitle = '';
		$fruitData = self::getFruitList();
		foreach($fruitData as $key=>$data){
			if($data['value'] == $fruit){
				$fruitTitle = $data['label'];
				break;
			}
		}
		
		return $fruitTitle;
	}
	
	
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
