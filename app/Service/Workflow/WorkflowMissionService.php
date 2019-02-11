<?php
/**
 *
 *  工作流
 *  任务
 */
class WorkflowMissionService extends Service{
	
	
	/**
	 *
	 * 任务列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getMissionList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('WorkflowMission')->where($where)->count();
		if($count){
			$revenueHandle = $this->model('WorkflowMission')->where($where)->orderby($orderby);
			if($perpage){
				$revenueHandle = $revenueHandle->limit($start,$perpage,$count);
			}
			$listdata = $revenueHandle->select();			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 任务信息
	 *
	 * @param $missionId 任务ID
	 *
	 * @reutrn array;
	 */
	public function getMissionData($missionId){
		
		$where = array(
			'identity'=>$missionId
		);
				
		return  $this->model('WorkflowMission')->where($where)->find();
	}
	
	/**
	 *
	 * 任务信息
	 *
	 * @param $missionId 任务ID
	 *
	 * @reutrn array;
	 */
	public function getMissionInfo($missionId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$missionId
		);
		
		$missionData = array();
		if(is_array($missionId)){
			$missionList = $this->model('WorkflowMission')->field($field)->where($where)->select();
			if($missionList){
				foreach($missionList as $key=>$mission){
					$missionData[$mission['identity']] = $mission;
				}
			}
		}else{
			$missionData = $this->model('WorkflowMission')->field($field)->where($where)->find();
		}
		return $missionData;
	}
	/**
	 *
	 * 新任务
	 *
	 * @param $missionId 任务ID
	 *
	 * @reutrn int;
	 */
	
	public function newMission($mission){
		return $this->insert($mission);
	}
	
	/**
	 *
	 * 删除任务
	 *
	 * @param $missionId 任务ID
	 *
	 * @reutrn int;
	 */
	public function removeMissionId($missionId){
		
		$output = 0;
		
		
		$missionData = $this->model('WorkflowMission')->where($where)->select();
		if($missionData){
			
			$output = $this->model('WorkflowMission')->where($where)->delete();
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 任务修改
	 *
	 * @param $missionId 任务ID
	 * @param $missionNewData 任务数据
	 *
	 * @reutrn int;
	 */
	public function update($missionNewData,$missionId){
		$where = array(
			'identity'=>$missionId
		);
		
		$missionData = $this->model('WorkflowMission')->where($where)->find();
		if($missionData){
			
			$missionNewData['lastupdate'] = $this->getTime();
			$this->model('WorkflowMission')->data($missionNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新任务
	 *
	 * @param $missionNewData 任务信息
	 *
	 * @reutrn int;
	 */
	public function insert($missionNewData){
		if(!$missionNewData){
			return -1;
		}
		
		$missionNewData['sn'] = $this->get_sn();
		$missionNewData['subscriber_identity'] =$this->session('uid');
		$missionNewData['dateline'] = $this->getTime();
		$missionNewData['lastupdate'] = $missionNewData['dateline'];
		
		$missionId = $this->model('WorkflowMission')->data($missionNewData)->add();
		
		return $missionId;
	}
}