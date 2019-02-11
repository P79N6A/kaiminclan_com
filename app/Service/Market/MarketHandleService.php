<?php
/**
 *
 * 操作记录
 *
 * 销售
 *
 */
class  MarketHandleService extends Service {
	
	/**
	 *
	 * 操作列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 操作列表;
	 */
	public function getHandleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('MarketHandle')->where($where)->count();
		if($count){
			$orderddHandle = $this->model('MarketHandle')->where($where)->orderby($orderby);
			if($start && $perpage){
				$orderddHandle = $orderddHandle->limit($start,$perpage,$count);
			}
			$listdata = $orderddHandle->select();
			
		
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	
	

	
	/**
	 *
	 * 删除操作
	 *
	 * @param $handleId 结算ID
	 *
	 * @reutrn int;
	 */
	public function removeHandleId($handleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$handleId
		);
		
		$handleData = $this->model('MarketHandle')->where($where)->select();
		if($handleData){
			
			$output = $this->model('MarketHandle')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 操作修改
	 *
	 * @param $orderddId 操作ID
	 * @param $orderddNewData 操作数据
	 *
	 * @reutrn int;
	 */
	public function update($orderddHandleData,$orderddId){
		$where = array(
			'identity'=>$orderddId
		);
		
		$orderddData = $this->model('MarketHandle')->where($where)->find();
		if($orderddData){
			
			$contactNewData['lastupdate'] = $this->getTime();
			$result = $this->model('MarketHandle')->data($orderddHandleData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新操作
	 *
	 * @param $orderddNewList 操作信息
	 * @param $receivablesId 付款码
	 *
	 * @reutrn int;
	 */
	public function insert($orderddHandleData){
		
		$uid = intval($this->session('uid'));
		$dateline = $this->getTime();
		
		$orderddTime = $orderddSubscrber = array();
		
		$orderddHandleData['subscriber_identity'] = $uid;
		$orderddHandleData['dateline'] = $dateline;
		$orderddHandleData['lastupdate'] = $dateline;
		
			
		$orderddId = $this->model('MarketHandle')->data($orderddHandleData)->add();
		return $orderddId;
		
	}
}