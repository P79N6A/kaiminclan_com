<?php
/**
 *
 *  工作流
 *  步骤
 */
class WorkflowProcedureService extends Service{
	
	
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
	public function getProcedureList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('WorkflowProcedure')->where($where)->count();
		if($count){
			$revenueHandle = $this->model('WorkflowProcedure')->where($where)->orderby($orderby);
			if($perpage){
				$revenueHandle = $revenueHandle->limit($start,$perpage,$count);
			}
			$listdata = $revenueHandle->select();			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	public function getBeginByProcessId($processId){
		
		$list = array();
		
		$where = array(
			'process_identity'=>$processId
		);
		
		$perpage = 2;
		
		$listdata = $this->model('WorkflowProcedure')->where($where)->limit(1,$perpage)->order('indexid ASC')->select();
		if($listdata){
			foreach($listdata as $key=>$data){
				$list[$data['indexid']] = $data;
			}
		}
		
		return $list;
	}
	
	public function getEndByProcessId($processId){
		
		$where = array(
			'process_identity'=>$processId,
			'style'=>WorkflowProcedureModel::WORKFLOW_PROCEDURE_STYLE_END
		);
		
		return $this->model('WorkflowProcedure')->where($where)->find();
	}
	
	public function getProcedureData($procedureId){
		
		$procedureId = $this->getInt($procedureId);
		if(!$procedureId){
			return array();
		}
		
		$where = array(
			'identity'=>$procedureId
		);
		
		
		return $this->model('WorkflowProcedure')->where($where)->find();
	}
	
	/**
	 *
	 * 事件信息
	 *
	 * @param $procedureId 事件ID
	 *
	 * @reutrn array;
	 */
	public function getProcedureInfo($procedureId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$procedureId
		);
		
		$procedureData = array();
		if(is_array($procedureId)){
			$procedureList = $this->model('WorkflowProcedure')->field($field)->where($where)->select();
			if($procedureList){
				foreach($procedureList as $key=>$procedure){
					$procedureData[$procedure['identity']] = $procedure;
				}
			}
		}else{
			$procedureData = $this->model('WorkflowProcedure')->field($field)->where($where)->find();
		}
		return $procedureData;
	}
	
	/**
	 *
	 * 删除事件
	 *
	 * @param $procedureId 事件ID
	 *
	 * @reutrn int;
	 */
	public function removeProcedureId($procedureId){
		
		$output = 0;
		
		
		$procedureData = $this->model('WorkflowProcedure')->where($where)->select();
		if($procedureData){
			
			$output = $this->model('WorkflowProcedure')->where($where)->delete();
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 事件修改
	 *
	 * @param $procedureId 事件ID
	 * @param $procedureNewData 事件数据
	 *
	 * @reutrn int;
	 */
	public function update($procedureNewData,$procedureId){
		$where = array(
			'identity'=>$procedureId
		);
		
		$procedureData = $this->model('WorkflowProcedure')->where($where)->find();
		if($procedureData){
			
			$procedureNewData['lastupdate'] = $this->getTime();
			$this->model('WorkflowProcedure')->data($procedureNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新事件
	 *
	 * @param $procedureNewData 事件信息
	 *
	 * @reutrn int;
	 */
	public function insert($procedureNewData){
		if(!$procedureNewData){
			return -1;
		}
		
		$procedureNewData['sn'] = $this->get_sn();
		$procedureNewData['subscriber_identity'] =$this->session('uid');
		$procedureNewData['dateline'] = $this->getTime();
		
		$procedureId = $this->model('WorkflowProcedure')->data($procedureNewData)->add();
		
		return $procedureId;
	}
}