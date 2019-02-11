<?php
/**
 *
 * 订单
 *
 * 销售
 *
 */
class  MarketOrderddService extends Service {
	
	/**
	 *
	 * 订单列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订单列表;
	 */
	public function getOrderddList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('MarketOrderdd')->where($where)->count();
		if($count){
			$orderddHandle = $this->model('MarketOrderdd')->where($where)->orderby($orderby);
			if($start && $perpage){
				$orderddHandle = $orderddHandle->limit($start,$perpage,$count);
			}
			$listdata = $orderddHandle->select();
			
			$contactIds = $subscriberIds = $settlementIds = $orderddIds = array();
			foreach($listdata as $key=>$orderdd){
				$orderddIds[] = $orderdd['identity'];
				$settlementIds[] = $orderdd['settlement_identity'];
				$subscriberIds[] = $orderdd['subscriber_identity'];
				if($orderdd['contact_identity']){
					$contactIds[] = $orderdd['contact_identity'];
				}
			}
			
			$shoppingList = $this->service('MarketShopping')->getShoppingByOrderddIds($orderddIds);
			$settlementData = $this->service('MarketSettlement')->getSettlementInfo($settlementIds);
			$contactData = $this->service('MarketContact')->getContactInfo($contactIds);
			$subscriberData = $this->service('AuthoritySubscriber')->getSubscriberInfo($subscriberIds);
					
			foreach($listdata as $key=>$orderdd){
				$listdata[$key]['shopping'] = $shoppingList[$orderdd['identity']];
				$listdata[$key]['settlement'] = $settlementData[$orderdd['settlement_identity']];
				$listdata[$key]['subscriber'] = $subscriberData[$orderdd['subscriber_identity']];
				$listdata[$key]['contact'] = $contactData[$orderdd['contact_identity']];
				$listdata[$key]['status'] = array(
					'value'=>$orderdd['status'],
					'label'=>MarketOrderddModel::getStatusTitle($orderdd['status'])
				);
				$listdata[$key]['distribution'] = array(
					'value'=>$orderdd['distribution'],
					'label'=>MarketOrderddModel::getDistributionTitle($orderdd['distribution'])
				);
				unset($listdata[$key]['settlement_identity']);
				unset($listdata[$key]['subscriber_identity']);
			}
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 按付款识别号获取订单
	 *
	 * @param $receivabledsId 付款ID
	 *
	 * @reutrn array;
	 */
	public function getOrderddIdByReceivabledsId($receivabledsId){
		$orderddIds = array();
		
		$receivabledsId = intval($receivabledsId);
		if(!$receivabledsId){
			return $orderddIds;
		}
		
		$where = array(
			'receivableds_identity'=>$receivabledsId
		);
		
		$listdata = $this->model('MarketOrderdd')->field('identity')->where($where)->select();
		if($listdata){
			foreach($listdata as $key=>$data){
				$orderddIds[] = $data['identity'];
			}
		}
		
		
		return $orderddIds;
	}
	
	/**
	 *
	 * 订单统计信息
	 *
	 * @param $uid 用户ID
	 *
	 * @reutrn array;
	 */
	public function getOrderddTotalByUid($uid){
		
		$uid = intval($uid);
		
		$where = array(
			'subscriber_identity'=>$uid
		);
		return $this->model('MarketOrderdd')->field('status,count(*) as total')->where($where)->group('status')->select();
	}
	
	/**
	 *
	 * 订单信息
	 *
	 * @param $orderddId 订单ID
	 *
	 * @reutrn array;
	 */
	public function getOrderddInfo($orderddId){
		
		$where = array(
			'identity'=>$orderddId
		);
		
		return $this->getOrderddList($where);
	}
	
	/**
	 *
	 * 订单基础信息
	 *
	 * @param $orderddId 订单ID
	 *
	 * @reutrn array;
	 */
	public function getOrderddBaseInfo($orderddId){
		
		$orderddData = array();
		
		$where = array(
			'identity'=>$orderddId
		);
		
		$orderddData = $this->model('MarketOrderdd')->where($where)->select();
		
		if(!is_array($orderddId)){
			$orderddData = current($orderddData);
		}
		
		return $orderddData;
	}
	
	/**
	 *
	 * 获取订单号
	 * @param int $cnt 序列号 
	 *
	 * @return int 订单号;
	 */
	public function getOrderddCode($cnt = 1){
		
		$cnt = intval($cnt);
		$cnt = $cnt < 1?1:$cnt;
		
		$where = array();
		$where['status'] = array('LT',MarketOrderddModel::MARKET_ORDERDD_STATUS_SHOPPING);
		
		$where['order_time'] = array('GT',strtotime(date('Y-m-d',strtotime('-1 day')))+(60*60*16-1));
		$count = $this->model('MarketOrderdd')->where()->count();
		
		return date('Ymd').sprintf('%06s', $count+$cnt);
	}
	
	/**
	 *
	 * 删除订单
	 *
	 * @param $settlementId 结算ID
	 *
	 * @reutrn int;
	 */
	public function removeOrderddId($settlementId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$settlementId
		);
		
		$settlementData = $this->model('MarketSettlement')->where($where)->select();
		if($settlementData){
			
			$output = $this->model('MarketSettlement')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 订单修改
	 *
	 * @param $orderddId 订单ID
	 * @param $orderddNewData 订单数据
	 *
	 * @reutrn int;
	 */
	public function update($orderddNewData,$orderddId){
		$where = array(
			'identity'=>$orderddId
		);
		
		$orderddData = $this->model('MarketOrderdd')->where($where)->find();
		if($orderddData){
			
			$contactNewData['lastupdate'] = $this->getTime();
			$result = $this->model('MarketOrderdd')->data($orderddNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新订单
	 *
	 * @param $orderddNewList 订单信息
	 * @param $receivablesId 付款码
	 *
	 * @reutrn int;
	 */
	public function insert($orderddNewList,$receivablesId){
		if(count($orderddNewList) < 1)
		{
			return -1;
		}
		
		$uid = intval($this->session('uid'));
		$dateline = $this->getTime();
		
		$orderddTime = $orderddSubscrber = array();
		
		foreach($orderddNewList as $key=>$orderdd){
			foreach($orderdd as $cnt=>$value){
				$orderddNewList['subscriber_identity'][$cnt] = $uid;
				$orderddNewList['dateline'][$cnt] = $dateline;
				$orderddNewList['lastupdate'][$cnt] = $dateline;
				$orderddNewList['receivableds_identity'][$cnt] = $receivablesId;
			}
		}
		
			
		$orderddId = $this->model('MarketOrderdd')->data($orderddNewList)->addMulti();
		return $orderddId;
		
	}
}