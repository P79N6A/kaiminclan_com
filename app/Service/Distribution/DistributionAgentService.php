<?php
/**
 *
 * 优惠卷
 *
 * 促销
 *
 */
class  DistributionAgentService extends Service {
	
	
	/**
	 *
	 * 活动列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getAgentList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('DistributionAgent')->where($where)->count();
		if($count){
			$agentHandle = $this->model('DistributionAgent')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$agentHandle = $agentHandle->limit($start,$perpage,$count);
			}
			$listdata = $agentHandle->select();
			$gradeIds = array();
			foreach($listdata as $key=>$data){
				$gradeIds[] = $data['grade_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>DistributionAgentModel::getStatusTitle($data['status'])
				);
			}
			$gradeData = $this->service('DistributionGrade')->getGradeInfo($gradeIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['grade'] = isset($gradeData[$data['grade_identity']])?$gradeData[$data['grade_identity']]:array();
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 活动信息
	 *
	 * @param $agentIds 活动ID
	 *
	 * @reutrn int;
	 */
	public function getAgentInfo($agentIds){
		$agentData = array();
		
		$where = array(
			'identity'=>$agentIds
		);
		
		$agentList = $this->model('DistributionAgent')->where($where)->select();
		if($agentList){
			
			if(is_array($agentIds)){
				$agentData = $agentList;
			}else{
				$agentData = current($agentList);
			}
			
			
		}
		
		
		return $agentData;
	}
	
	
		
	/**
	 *
	 * 删除活动
	 *
	 * @param $agentId 活动ID
	 *
	 * @reutrn int;
	 */
	public function removeAgentId($agentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$agentId
		);
		
		$agentData = $this->model('DistributionAgent')->where($where)->count();
		if($agentData){
			
			$output = $this->model('DistributionAgent')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测活动
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkAgentTitle($title){
		$agentId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('DistributionAgent')->where($where)->count();
	}
	
	/**
	 *
	 * 活动修改
	 *
	 * @param $agentId 活动ID
	 * @param $agentNewData 活动数据
	 *
	 * @reutrn int;
	 */
	public function update($agentNewData,$agentId){
		$where = array(
			'identity'=>$agentId
		);
		
		$agentData = $this->model('DistributionAgent')->where($where)->find();
		if($agentData){
			
			
			$agentNewData['lastupdate'] = $this->getTime();
			$this->model('DistributionAgent')->data($agentNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新活动
	 *
	 * @param $id 活动信息
	 * @param $idtype 活动信息
	 *
	 * @reutrn int;
	 */
	public function insert($agentData){
		$dateline = $this->getTime();
		$agentData['subscriber_identity'] = $this->session('uid');
		$agentData['dateline'] = $dateline;
		$agentData['lastupdate'] = $dateline;
		$agentData['sn'] = $this->get_sn();
			
		$agentId = $this->model('DistributionAgent')->data($agentData)->add();
		if($agentId){
		}
		return $agentId;
	}
}