<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProductionProcessService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getProcessList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProductionProcess')->where($where)->count();
		if($count){
			$handle = $this->model('ProductionProcess')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkProcessTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProductionProcess')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $processId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getProcessInfo($processId,$field = '*'){
		
		$where = array(
			'identity'=>$processId
		);
		
		$processData = $this->model('ProductionProcess')->field($field)->where($where)->find();
		
		return $processData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $processId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeProcessId($processId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$processId
		);
		
		$processData = $this->model('ProductionProcess')->where($where)->find();
		if($processData){
			
			$output = $this->model('ProductionProcess')->where($where)->delete();
			
			$this->service('PaginationItem')->removeProcessIdAllItem($processId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $processId 模块ID
	 * @param $processNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($processNewData,$processId){
		$where = array(
			'identity'=>$processId
		);
		
		$processData = $this->model('ProductionProcess')->where($where)->find();
		if($processData){
			
			$processNewData['lastupdate'] = $this->getTime();
			$this->model('ProductionProcess')->data($processNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $processNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($processNewData){
		
		$processNewData['subscriber_identity'] =$this->session('uid');
		$processNewData['dateline'] = $this->getTime();
		$processNewData['sn'] = $this->get_sn();
			
		$processNewData['lastupdate'] = $processNewData['dateline'];
		$processId = $this->model('ProductionProcess')->data($processNewData)->add();
		
		return $processId;
	}
}