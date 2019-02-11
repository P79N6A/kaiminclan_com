<?php
/**
 *
 *  工作流
 *  流程
 */
class WorkflowProcessService extends Service{
	
	
	/**
	 *
	 * 事件列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getProcessList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('WorkflowProcess')->where($where)->count();
		if($count){
			$revenueHandle = $this->model('WorkflowProcess')->where($where)->orderby($orderby);
			if($perpage){
				$revenueHandle = $revenueHandle->limit($start,$perpage,$count);
			}
			$listdata = $revenueHandle->select();			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	public function getProcessData($processId){
		
		$processId = $this->getInt($processId);
		if(!$processId){
			return array();
		}
		
		$where = array(
			'identity'=>$processId,
			'status'=>WorkflowProcessModel::WORKFLOW_MISSION_STATUS_ENABLE
		);
		
		
		return $this->model('WorkflowProcess')->where($where)->find();
	}
	
	/**
	 *
	 * 事件信息
	 *
	 * @param $processId 事件ID
	 *
	 * @reutrn array;
	 */
	public function getProcessInfo($processId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$processId
		);
		
		$processData = array();
		if(is_array($processId)){
			$processList = $this->model('WorkflowProcess')->field($field)->where($where)->select();
			if($processList){
				foreach($processList as $key=>$process){
					$processData[$process['identity']] = $process;
				}
			}
		}else{
			$processData = $this->model('WorkflowProcess')->field($field)->where($where)->find();
		}
		return $processData;
	}
	
	/**
	 *
	 * 删除事件
	 *
	 * @param $processId 事件ID
	 *
	 * @reutrn int;
	 */
	public function removeProcessId($processId){
		
		$output = 0;
		
		
		$processData = $this->model('WorkflowProcess')->where($where)->select();
		if($processData){
			
			$output = $this->model('WorkflowProcess')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 检测模块名称
	 *
	 * @param $layoutName 模块名称
	 *
	 * @reutrn int;
	 */
	public function checkProcessTitle($layoutName){
		if($layoutName){
			$where = array(
				'title'=>$layoutName
			);
			return $this->model('WorkflowProcess')->where($where)->count();
		}
		return 0;
	}
	
	
	/**
	 *
	 * 事件修改
	 *
	 * @param $processId 事件ID
	 * @param $processNewData 事件数据
	 *
	 * @reutrn int;
	 */
	public function update($processNewData,$processId){
		$where = array(
			'identity'=>$processId
		);
		
		$processData = $this->model('WorkflowProcess')->where($where)->find();
		if($processData){
			
			$processNewData['lastupdate'] = $this->getTime();
			$this->model('WorkflowProcess')->data($processNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新事件
	 *
	 * @param $processNewData 事件信息
	 *
	 * @reutrn int;
	 */
	public function insert($processNewData){
		if(!$processNewData){
			return -1;
		}
		
		$processNewData['sn'] = $this->get_sn();
		$processNewData['subscriber_identity'] =$this->session('uid');
		$processNewData['dateline'] = $this->getTime();
		
		$processId = $this->model('WorkflowProcess')->data($processNewData)->add();
		
		return $processId;
	}
}