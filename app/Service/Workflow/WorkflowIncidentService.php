<?php
/**
 *
 *  工作流
 *
 */
class WorkflowIncidentService extends Service{
	
	
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
	public function getIncidentList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('WorkflowIndicent')->where($where)->count();
		if($count){
			$revenueHandle = $this->model('WorkflowIndicent')->where($where)->orderby($orderby);
			if($perpage){
				$revenueHandle = $revenueHandle->limit($start,$perpage,$count);
			}
			$listdata = $revenueHandle->select();			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 事件信息
	 *
	 * @param $indicentId 事件ID
	 *
	 * @reutrn array;
	 */
	public function getRoleInfo($indicentId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$indicentId
		);
		
		$indicentData = array();
		if(is_array($indicentId)){
			$indicentList = $this->model('WorkflowIndicent')->field($field)->where($where)->select();
			if($indicentList){
				foreach($indicentList as $key=>$indicent){
					$indicentData[$indicent['identity']] = $indicent;
				}
			}
		}else{
			$indicentData = $this->model('WorkflowIndicent')->field($field)->where($where)->find();
		}
		return $indicentData;
	}
	
	/**
	 *
	 * 删除事件
	 *
	 * @param $indicentId 事件ID
	 *
	 * @reutrn int;
	 */
	public function removeRoleId($indicentId){
		
		$output = 0;
		
		
		$indicentData = $this->model('WorkflowIndicent')->where($where)->select();
		if($indicentData){
			
			$output = $this->model('WorkflowIndicent')->where($where)->delete();
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 事件修改
	 *
	 * @param $indicentId 事件ID
	 * @param $indicentNewData 事件数据
	 *
	 * @reutrn int;
	 */
	public function update($indicentNewData,$indicentId){
		$where = array(
			'identity'=>$indicentId
		);
		
		$indicentData = $this->model('WorkflowIndicent')->where($where)->find();
		if($indicentData){
			
			$indicentNewData['lastupdate'] = $this->getTime();
			$this->model('WorkflowIndicent')->data($indicentNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新事件
	 *
	 * @param $indicentNewData 事件信息
	 *
	 * @reutrn int;
	 */
	public function insert($indicentNewData){
		if(!$indicentNewData){
			return -1;
		}
		
		$indicentNewData['address'] = $this->getClientIp();
		$indicentNewData['sn'] = $this->get_sn();
		$indicentNewData['subscriber_identity'] =$this->session('uid');
		$indicentNewData['dateline'] = $this->getTime();
		
		$indicentId = $this->model('WorkflowIndicent')->data($indicentNewData)->add();
		
		return $indicentId;
	}
}