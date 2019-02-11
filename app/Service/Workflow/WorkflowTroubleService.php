<?php
/**
 *
 *  工作流
 *  流程
 */
class WorkflowTroubleService extends Service{
	
	
	/**
	 *
	 * 事列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getTroubleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('WorkflowTrouble')->where($where)->count();
		if($count){
			$revenueHandle = $this->model('WorkflowTrouble')->where($where)->orderby($orderby);
			if($perpage){
				$revenueHandle = $revenueHandle->limit($start,$perpage,$count);
			}
			$listdata = $revenueHandle->select();			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	public function checkTroubleExists($processId,$id,$idtype){
		
		$processId = $this->getInt($processId);
		$id = $this->getInt($id);
		$idtype = $this->getInt($idtype);
		
		$where = array(
			'process_identity'=>$processId,
			'id'=>$id,
			'idtype'=>$idtype
		);
		$troubleTotal = $this->model('WorkflowTrouble')->where($where)->count();
		
		return $troubleTotal;
	}
	
	public function getTroubleData($troubleId){
		
		$troubleId = $this->getInt($troubleId);
		if(!$troubleId){
			return array();
		}
		
		$where = array(
			'identity'=>$troubleId,
			'status'=>WorkflowTroubleModel::WORKFLOW_MISSION_STATUS_ENABLE
		);
		
		
		return $this->model('WorkflowTrouble')->where($where)->find();
	}
	
	/**
	 *
	 * 事信息
	 *
	 * @param $troubleId 事ID
	 *
	 * @reutrn array;
	 */
	public function getTroubleInfo($troubleId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$troubleId
		);
		
		$troubleData = array();
		if(is_array($troubleId)){
			$troubleList = $this->model('WorkflowTrouble')->field($field)->where($where)->select();
			if($troubleList){
				foreach($troubleList as $key=>$trouble){
					$troubleData[$trouble['identity']] = $trouble;
				}
			}
		}else{
			$troubleData = $this->model('WorkflowTrouble')->field($field)->where($where)->find();
		}
		return $troubleData;
	}
	
	/**
	 *
	 * 删除事
	 *
	 * @param $troubleId 事ID
	 *
	 * @reutrn int;
	 */
	public function removeTroubleId($troubleId){
		
		$output = 0;
		
		
		$troubleData = $this->model('WorkflowTrouble')->where($where)->select();
		if($troubleData){
			
			$output = $this->model('WorkflowTrouble')->where($where)->delete();
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 事修改
	 *
	 * @param $troubleId 事ID
	 * @param $troubleNewData 事数据
	 *
	 * @reutrn int;
	 */
	public function update($troubleNewData,$troubleId){
		$where = array(
			'identity'=>$troubleId
		);
		
		$troubleData = $this->model('WorkflowTrouble')->where($where)->find();
		if($troubleData){
			
			$troubleNewData['lastupdate'] = $this->getTime();
			$this->model('WorkflowTrouble')->data($troubleNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新事
	 *
	 * @param $troubleNewData 事信息
	 *
	 * @reutrn int;
	 */
	public function insert($troubleNewData){
		if(!$troubleNewData){
			return -1;
		}
		
		$troubleNewData['sn'] = $this->get_sn();
		$troubleNewData['subscriber_identity'] =$this->session('uid');
		$troubleNewData['dateline'] = $this->getTime();
		
		$troubleId = $this->model('WorkflowTrouble')->data($troubleNewData)->add();
		
		return $troubleId;
	}
}