<?php
/**
 *
 * 任务
 *
 * 基础
 *
 */
class  FoundationMissionService extends Service {
	
	
	/**
	 *
	 * 任务信息
	 *
	 * @param $field 任务字段
	 * @param $status 任务状态
	 *
	 * @reutrn array;
	 */
	public function getMissionList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('FoundationMission')->where($where)->count();
		if($count){
			$subsidiaryHandle = $this->model('FoundationMission')->where($where)->orderby($orderby);
			if($perpage){
				$subsidiaryHandle = $subsidiaryHandle->limit($start,$perpage,$count);
			}
			$listdata = $subsidiaryHandle->select();
			
			
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
	public function getMissionInfo($missionId){
		
		$missionData = array();
		
		$where = array(
			'identity'=>$missionId
		);
		
		$missionList = $this->model('FoundationMission')->where($where)->select();
		if($missionList){
			foreach($missionList as $key=>$mission){
				$missionList[$key]['rules'] = json_decode($mission['rules'],true);
			}
		}
		if(!is_array($missionId)){
			$missionData = current($missionList);
			$missionData[$missionId] = $missionData;
		}else{
			foreach($missionList as $key=>$mission){	
				$missionData[$mission['identity']] = $mission;
			}
		}
		
		return $missionData;
	}
	/**
	 *
	 * 检测任务
	 *
	 * @param $title 任务标题
	 *
	 * @reutrn int;
	 */
	public function checkMissionName($title){
		if($title){
			$where = array(
				'title'=>$title,
			);
			return $this->model('FoundationMission')->where($where)->count();
		}
		return 0;
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
		
		$where = array(
			'identity'=>$missionId
		);
		
		$missionData = $this->model('FoundationMission')->where($where)->select();
		if($missionData){
			$output = $this->model('FoundationMission')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 取消默认任务
	 *
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function cannelDefaultMissionByUid($uid){
		$where = array(
			'subscriber_identity'=>$uid
		);
		$missionNewData = array(
			'secleted'=>FoundationMissionModel::MARKET_CONTACT_SELECTED_NO
		);
		$missionNewData['lastupdate'] = $this->getTime();
		$result = $this->model('FoundationMission')->data($missionNewData)->where($where)->save();
			
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
		
		$missionData = $this->model('FoundationMission')->where($where)->find();
		if($missionData){
			
			$missionNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FoundationMission')->data($missionNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
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
		$missionNewData['subscriber_identity'] =$this->session('uid');		
		$missionNewData['dateline'] = $this->getTime();
			
		$missionNewData['lastupdate'] = $missionNewData['dateline'];
		$missionId = $this->model('FoundationMission')->data($missionNewData)->add();
		
		
	}
}