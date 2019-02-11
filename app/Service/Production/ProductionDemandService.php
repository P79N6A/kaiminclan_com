<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProductionDemandService extends Service
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
	public function getDemandList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProductionDemand')->where($where)->count();
		if($count){
			$handle = $this->model('ProductionDemand')->where($where);
			if($perpage > 0){
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
	public function checkDemandTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProductionDemand')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $demandId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getDemandInfo($demandId,$field = '*'){
		
		$where = array(
			'identity'=>$demandId
		);
		
		$demandData = $this->model('ProductionDemand')->field($field)->where($where)->find();
		
		return $demandData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $demandId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeDemandId($demandId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$demandId
		);
		
		$demandData = $this->model('ProductionDemand')->where($where)->find();
		if($demandData){
			
			$output = $this->model('ProductionDemand')->where($where)->delete();
			
			$this->service('PaginationItem')->removeDemandIdAllItem($demandId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $demandId 模块ID
	 * @param $demandNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($demandNewData,$demandId){
		$where = array(
			'identity'=>$demandId
		);
		
		$demandData = $this->model('ProductionDemand')->where($where)->find();
		if($demandData){
			
			$demandNewData['lastupdate'] = $this->getTime();
			$this->model('ProductionDemand')->data($demandNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $demandNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($demandNewData){
		
		$demandNewData['subscriber_identity'] =$this->session('uid');
		$demandNewData['dateline'] = $this->getTime();
		$demandNewData['sn'] = $this->get_sn();
			
		$demandNewData['lastupdate'] = $demandNewData['dateline'];
		$demandId = $this->model('ProductionDemand')->data($demandNewData)->add();
		
		return $demandId;
	}
}