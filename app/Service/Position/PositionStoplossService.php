<?php
/**
 *
 * 开仓
 *
 * 头寸
 *
 */
class  PositionStoplossService extends Service {
	
	
	
	/**
	 *
	 * 开仓列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getPurchaseList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PositionStoploss')->where($where)->count();
		if($count){
			$stoplossHandle = $this->model('PositionStoploss')->where($where)->orderby($orderby);
			if($perpage){
				$stoplossHandle = $stoplossHandle->limit($start,$perpage,$count);
			}
			$listdata = $stoplossHandle->select();
			
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 平仓
	 *
	 * @param $stoplossIds 开仓ID
	 *
	 * @reutrn int;
	 */
	public function close($stoplossId){
		
		
		$where = array(
			'identity'=>$stoplossId
		);
		
		$setarr = array(
			'status'=>PositionStoplossModel::POSITION_PURCHASE_STATUS_CLOSED
		);
		
		return $this->model('PositionStoploss')->data($setarr)->where($where)->save();
	}
	/**
	 *
	 * 开仓信息
	 *
	 * @param $stoplossIds 开仓ID
	 *
	 * @reutrn int;
	 */
	public function getPurchaseByLast($accountId,$symbolId){
		
		
		$where = array(
			'account_identity'=>$accountId,
			'symbol_identity'=>$symbolId,
			'status'=>PositionStoplossModel::POSITION_PURCHASE_STATUS_WAIT
		);
		
		return $this->model('PositionStoploss')->where($where)->order('identity DESC')->select();
	}
	/**
	 *
	 * 开仓信息
	 *
	 * @param $ticket 开仓ID
	 *
	 * @reutrn int;
	 */
	public function getPurchaaseByTicket($ticket){
		
		
		$where = array(
			'code'=>$ticket,
		);
		
		return $this->model('PositionStoploss')->where($where)->find();
	}
	
	/**
	 *
	 * 开仓信息
	 *
	 * @param $stoplossIds 开仓ID
	 *
	 * @reutrn int;
	 */
	public function getPurchaseInfo($stoplossIds){
		$stoplossData = array();
		
		$where = array(
			'identity'=>$stoplossIds
		);
		
		$stoplossList = $this->model('PositionStoploss')->where($where)->select();
		if($stoplossList){
			
			if(is_array($stoplossIds)){
				$stoplossData = $stoplossList;
			}else{
				$stoplossData = current($stoplossList);
			}
			
			
		}
		
		
		return $stoplossData;
	}
	
	
		
	/**
	 *
	 * 删除开仓
	 *
	 * @param $stoplossId 开仓ID
	 *
	 * @reutrn int;
	 */
	public function removePurchaseId($stoplossId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$stoplossId
		);
		
		$stoplossData = $this->model('PositionStoploss')->where($where)->count();
		if($stoplossData){
			
			$output = $this->model('PositionStoploss')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测开仓
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkPurchase($code,$idType,$id){
		$stoplossId = array();		
		$where = array(
			'code'=>$code,
			'idType'=>intval($idType),
			'id'=>intval($id),
		);
		
		
		return $this->model('PositionStoploss')->where($where)->count();
	}
	
	/**
	 *
	 * 开仓修改
	 *
	 * @param $stoplossId 开仓ID
	 * @param $stoplossNewData 开仓数据
	 *
	 * @reutrn int;
	 */
	public function update($stoplossNewData,$stoplossId){
		$where = array(
			'identity'=>$stoplossId
		);
		
		$stoplossData = $this->model('PositionStoploss')->where($where)->find();
		if($stoplossData){
			
			
			$stoplossNewData['lastupdate'] = $this->getTime();
			$this->model('PositionStoploss')->data($stoplossNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新开仓
	 *
	 * @param $id 开仓信息
	 * @param $idtype 开仓信息
	 *
	 * @reutrn int;
	 */
	public function insert($oldStopLoss,$newStopLoss){
		
		$dateline = $this->getTime();
		$stoplossData['subscriber_identity'] = $this->session('uid');
		$stoplossData['dateline'] = $dateline;
		$stoplossData['quotation'] = $oldStopLoss;
		$stoplossData['univalent'] = $newStopLoss;
		$stoplossData['sn'] = $this->get_sn();
		
		$stoplossId = $this->model('PositionStoploss')->data($stoplossData)->add();
		
		return $stoplossId;
		
	}
}