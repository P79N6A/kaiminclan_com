<?php
/**
 *
 *  工作流
 *
 *  发起流程，
 *  任务处理，
 *  	退回（初始发起人，上一流程执行人）
 *		事件确认（同意，反对，指派）
 *		数据提供
 *  	自动任务，
 *  结束流程
 *
 *  基础（定义）
 *  流程  步骤
 *
 *  工作
 *  任务（任务责任人）
 *  事件（执行过程）
 *
 *
 */
class WorkflowService extends Service{
	/**
	 * 发起流程
	 */
	public function start($processId,$idtype,$id){
		
		$processId = $this->getInt($processId);
		
		if(!$processId){
			return -1;
		}
		$idtype = $this->getInt($idtype);
		
		if(!$idtype){
			return -2;
		}
		$id = $this->getInt($id);
		
		if(!$id){
			return -3;
		}
		
		$processData = $this->service('WorkflowProcess')->getProcessData($processId);
		if(!$processData){
			return -4;
		}
		if($this->service('WorkflowTrouble')->checkTroubleExists($processId,$id,$idtype)){
			return -5;
		}
		
		
		
		$procedureData = $this->service('WorkflowProcedure')->getBeginByProcessId($processData['identity']);
		if(!$procedureData){
			return -6;
		}
		
		list($userType,$userId) = $this->getUser($procedureData['user_type'],$procedureData['user_id']);		
		
		$troubleId = $this->service('WorkflowTrouble')->insert(array('process_identity'=>$processId,'idtype'=>$idtype,'id'=>$id));
		
		$missionId = $this->newTask('开始',$troubleId,$procedureData[1]['identity'],$processId,$userType,$userId);
		
		$cnt = 2;
		while($cnt < 3){
			$missionId = $this->execute($missionId,1);
			$cnt++;
		}
		
		return $missionId;				
	}
	
	public function getUser($userType,$userId){
		
		return array($userType,$userId);
		$procedureUserId = 0;		
		$roleId = $this->session('roleId');
		$uid = $this->getUID();
		$procedureUserType = $userType;
		
		switch($userType){
			case WorkflowProcedureModel::WORKFLOW_PROCEDURE_USER_TYPE_ROLE:
				//指定角色
				if($roleId != $userId){
					return -7;
				}
				$procedureUserId = $roleId;
				break;
			case WorkflowProcedureModel::WORKFLOW_PROCEDURE_USER_TYPE_USER:
				//指定用户
				if($uid != $userId){
					return -8;
				}
				$procedureUserId = $uid;
				break;
		}
		
		return array($procedureUserType,$procedureUserId);
		
	}
	/**
	 * 结束流程
	 */
	public function stop($troubleId){
		
		$troubleId = $this->getInt($troubleId);
		
		if(!$troubleId){
			return -1;
		}
		
		$troubleData = array(
			'status'=>WorkflowTroubleModel::WORKFLOW_TROUBLE_STATUS_FINISH
		);
		
		$this->service('WorkflowTrouble')->update($troubleData,$troubleId);
		return 0;
	}
	/**
	 * 任务处理
	 * @param $missionId 任务ID
	 * @param $fruit 状态
	 * @param $opinion 意见
	 *
	 */
	public function execute($missionId,$fruit,$opinion = '',$_missionId = 0){
		
		$missionId = $this->getInt($missionId);
		if($missionId < 1){
			return -1;
		}
		
		$missionData = $this->service('WorkflowMission')->getMissionData($missionId);
		if(!$missionData){
			return -2;
		}
		if($missionData['status'] == WorkflowMissionModel::WORKFLOW_MISSION_STATUS_FINISH){
			return -3;
		}
		
		//流程ID
		$processId = $missionData['process_identity'];
		$processData = $this->service('WorkflowProcess')->getProcessData($processId);
		if(!$processData){
			return -4;
		}
		//步骤ID
		$procedureId = $missionData['procedure_identity'];
		$procedureData = $this->service('WorkflowProcedure')->getProcedureData($procedureId);
		if(!$processData){
			return -5;
		}
		
		
		//根据本次执行结果，确认下一步流程
		
		switch($fruit){
			//退回流程发起人
			case WorkflowMissionModel::WORKFLOW_MISSION_FRUIT_INITIATOR:
				$procedureData = $this->service('WorkflowProcedure')->getBeginByProcessId($processId);
				$nextProcedureId = $procedureData['next_item_identity'];
			break;
			//退回到上一步骤
			case WorkflowMissionModel::WORKFLOW_MISSION_FRUIT_PREV:
				$nextProcedureId = $procedureData['prev_item_identity'];				
			break;
			//同意
			case WorkflowMissionModel::WORKFLOW_MISSION_FRUIT_AGREE:
				$nextProcedureId = $procedureData['next_item_identity'];
			break;
		}		
		
		//得到流程信息，执行任务分派			
		$nextProcedureData = $this->service('WorkflowProcedure')->getProcedureData($nextProcedureId);
		if(!$nextProcedureData){
			return -7;
		}
		
		//不允许退回到开始流程
		switch($fruit){
			case WorkflowMissionModel::WORKFLOW_MISSION_FRUIT_INITIATOR:
			case WorkflowMissionModel::WORKFLOW_MISSION_FRUIT_PREV:
				if(in_array($nextProcedureData['style'],array(WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_START))){
					$fruit = WorkflowMissionModel::WORKFLOW_MISSION_FRUIT_AGREE;	
					$nextProcedureId = $procedureData['next_item_identity'];
					$nextProcedureData = $this->service('WorkflowProcedure')->getProcedureData($nextProcedureId);
					if(!$nextProcedureData){
						return -7;
					}
				}
			break;
		}
		
		$this->service('WorkflowMission')->update(array('status'=>WorkflowMissionModel::WORKFLOW_MISSION_STATUS_FINISH,'fruit'=>$fruit,'opinion'=>$opinion),$missionId);
		
		
		if(!in_array($nextProcedureData['style'],array(WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_START,WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_END))){
		
			list($userType,$userId) = $this->getUser($nextProcedureData['user_type'],$nextProcedureData['user_id']);
			$missionId = $this->newTask($nextProcedureData['title'],$missionData['trouble_identity'],$nextProcedureData['identity'],$processId,$userType,$userId,$procedureData['style']);
		}
		switch($nextProcedureData['style']){
			case WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_AUTO:
				//自动执行
				$result = $this->_auto($procedureData['script']);
				$missionId = $this->execute($missionId,$result['fruit'],$result['opinion']);
			break;
		}
		return $missionId;
	}
	/**
	 * 自动任务
	 */
	private function _auto($script){
		if(!$script){
			return array('fruit'=>1,'opinion'=>'');
		}
		//$this->task($script);
		
		return ;
	}
	/**
	 * 任务分派
	 */
	public function newTask($title,$troubleId,$procedureId,$processId,$accountType,$accountId,$style){
		$missionData = array(
			'trouble_identity'=>$troubleId,
			'procedure_identity'=>$procedureId,
			'process_identity'=>$processId,
			'account_type'=>$subscriberId,
			'account_id'=>$account_id,
			'title'=>$title
		);	
		$missionId = $this->service('WorkflowMission')->newMission($missionData);
		if($missionId){
			switch($style){
				case WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_START:
					//开始
					$this->start($processId);
				break;
				case WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_END:
					//结束
					$this->stop($troubleId);
				break;
			}
		}
		return $missionId;
	}
}