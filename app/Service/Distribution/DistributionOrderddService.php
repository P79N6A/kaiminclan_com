<?php
/**
 *
 * 等级
 *
 * 分销
 *
 */
class  DistributionOrderddService extends Service {
	
	
	/**
	 *
	 * 分销订单列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getOrderddList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('DistributionOrderdd')->where($where)->count();
		if($count){
			$orderddHandle = $this->model('DistributionOrderdd')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$orderddHandle = $orderddHandle->limit($start,$perpage,$count);
			}
			$listdata = $orderddHandle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>DistributionOrderddModel::getStatusTitle($data['status'])
				);
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 分销订单信息
	 *
	 * @param $orderddIds 分销订单ID
	 *
	 * @reutrn int;
	 */
	public function getOrderddInfo($orderddIds){
		$orderddData = array();
		
		$where = array(
			'identity'=>$orderddIds
		);
		
		$orderddList = $this->model('DistributionOrderdd')->where($where)->select();
		if($orderddList){
			
			if(is_array($orderddIds)){
				$orderddData = $orderddList;
			}else{
				$orderddData = current($orderddList);
			}
			
			
		}
		
		
		return $orderddData;
	}
	
	
		
	/**
	 *
	 * 删除分销订单
	 *
	 * @param $orderddId 分销订单ID
	 *
	 * @reutrn int;
	 */
	public function removeOrderddId($orderddId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$orderddId
		);
		
		$orderddData = $this->model('DistributionOrderdd')->where($where)->count();
		if($orderddData){
			
			$output = $this->model('DistributionOrderdd')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测分销订单
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkOrderddTitle($title){
		$orderddId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('DistributionOrderdd')->where($where)->count();
	}
	
	/**
	 *
	 * 分销订单修改
	 *
	 * @param $orderddId 分销订单ID
	 * @param $orderddNewData 分销订单数据
	 *
	 * @reutrn int;
	 */
	public function update($orderddNewData,$orderddId){
		$where = array(
			'identity'=>$orderddId
		);
		
		$orderddData = $this->model('DistributionOrderdd')->where($where)->find();
		if($orderddData){
			
			
			$orderddNewData['lastupdate'] = $this->getTime();
			$this->model('DistributionOrderdd')->data($orderddNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新分销订单
	 *
	 * @param $id 分销订单信息
	 * @param $idtype 分销订单信息
	 *
	 * @reutrn int;
	 */
	public function insert($orderddData){
		$dateline = $this->getTime();
		$orderddData['subscriber_identity'] = $this->session('uid');
		$orderddData['dateline'] = $dateline;
		$orderddData['lastupdate'] = $dateline;
		$orderddData['sn'] = $this->get_sn();
			
		$orderddId = $this->model('DistributionOrderdd')->data($orderddData)->add();
		if($orderddId){
		}
		return $orderddId;
	}
}