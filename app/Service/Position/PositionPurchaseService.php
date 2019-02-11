<?php
/**
 *
 * 开仓
 *
 * 头寸
 *
 */
class  PositionPurchaseService extends Service {
	
	
	
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
		
		$count = $this->model('PositionPurchase')->where($where)->count();
		if($count){
			$purchaseHandle = $this->model('PositionPurchase')->where($where)->orderby($orderby);
			if($perpage){
				$purchaseHandle = $purchaseHandle->limit($start,$perpage,$count);
			}
			$listdata = $purchaseHandle->select();
			
			$symbolIds = array();
			foreach($listdata as $key=>$purchase){
				$idTypeData[$purchase['idtype']][] = $purchase['id'];
				$listdata[$key]['status'] = array(
					'value'=>$purchase['status'],
					'label'=>PositionPurchaseModel::getStatusTitle($purchase['status'])
				);
                $listdata[$key]['style'] = array(
                    'value'=>$purchase['style'],
                    'label'=>PositionPurchaseModel::getStyleTitle($purchase['style'])
                );
			}
			
			foreach($idTypeData as $idtype=>$ids){
				switch($idtype){
					case PositionPurchaseModel::POSITION_PURCHASE_IDTYPE_STOCK:
						$targetData = $this->service('SecuritiesStock')->getStockInfo($ids,'identity,title');
						break;
					case PositionPurchaseModel::POSITION_PURCHASE_IDTYPE_FOREX:
						$targetData = $this->service('ForeignContact')->getContactInfo($ids,'identity,title');
						break;
					case PositionPurchaseModel::POSITION_PURCHASE_IDTYPE_BOND:
						$targetData =$this->service('DebentureBond')->getBondInfo($ids,'identity,title');
						break;
					case PositionPurchaseModel::POSITION_PURCHASE_IDTYPE_FUTURES:
						$targetData = $this->service('MaterialContract')->getContractInfo($ids,'identity,title');
						break;
				}
				
				foreach($listdata as $key=>$data){
					if($data['idtype'] != $idtype) continue;
					$listdata[$key]['target'] = $targetData[$data['id']];
				}
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 平仓
	 *
	 * @param $purchaseIds 开仓ID
	 *
	 * @reutrn int;
	 */
	public function close($purchaseId){
		
		
		$where = array(
			'identity'=>$purchaseId
		);
		
		$setarr = array(
			'status'=>PositionPurchaseModel::POSITION_PURCHASE_STATUS_CLOSED
		);
		
		return $this->model('PositionPurchase')->data($setarr)->where($where)->save();
	}
	/**
	 *
	 * 开仓信息
	 *
	 * @param $purchaseIds 开仓ID
	 *
	 * @reutrn int;
	 */
	public function getPurchaseByLast($accountId,$symbolId){
		
		
		$where = array(
			'account_identity'=>$accountId,
			'symbol_identity'=>$symbolId,
			'status'=>PositionPurchaseModel::POSITION_PURCHASE_STATUS_WAIT
		);
		
		return $this->model('PositionPurchase')->where($where)->order('identity DESC')->select();
	}
	/**
	 *
	 * 开仓信息
	 *
	 * @param $ticket 开仓ID
	 *
	 * @reutrn int;
	 */
	public function getPurchaseByTicket($ticket){
		
		
		$where = array(
			'code'=>$ticket,
		);
		
		return $this->model('PositionPurchase')->where($where)->find();
	}
	
	/**
	 *
	 * 开仓信息
	 *
	 * @param $purchaseIds 开仓ID
	 *
	 * @reutrn int;
	 */
	public function getPurchaseInfo($purchaseIds){
		$purchaseData = array();
		
		$where = array(
			'identity'=>$purchaseIds
		);
		
		$purchaseList = $this->model('PositionPurchase')->where($where)->select();
		if($purchaseList){
			
			if(is_array($purchaseIds)){
				$purchaseData = $purchaseList;
			}else{
				$purchaseData = current($purchaseList);
			}
			
			
		}
		
		
		return $purchaseData;
	}
	
	
		
	/**
	 *
	 * 删除开仓
	 *
	 * @param $purchaseId 开仓ID
	 *
	 * @reutrn int;
	 */
	public function removePurchaseId($purchaseId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$purchaseId
		);
		
		$purchaseData = $this->model('PositionPurchase')->where($where)->count();
		if($purchaseData){
			
			$output = $this->model('PositionPurchase')->where($where)->delete();
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
		$purchaseId = array();		
		$where = array(
			'code'=>$code,
			'idType'=>intval($idType),
			'id'=>intval($id),
		);
		
		
		return $this->model('PositionPurchase')->where($where)->count();
	}
	
	/**
	 *
	 * 开仓修改
	 *
	 * @param $purchaseId 开仓ID
	 * @param $purchaseNewData 开仓数据
	 *
	 * @reutrn int;
	 */
	public function update($purchaseNewData,$purchaseId){
		$where = array(
			'identity'=>$purchaseId
		);
		
		$purchaseData = $this->model('PositionPurchase')->where($where)->find();
		if($purchaseData){
			
			
			$purchaseNewData['lastupdate'] = $this->getTime();
			$this->model('PositionPurchase')->data($purchaseNewData)->where($where)->save();
			if($purchaseData['symbol_identity'] != $purchaseNewData['symbol_identity']){
				$this->service('PositionInventory')->reduceInventory($purchaseData['symbol_identity'],$purchaseData['quantity']);
				$this->service('PositionInventory')->addInventory($purchaseNewData['symbol_identity'],$purchaseNewData['quantity']);
			}else{
				$isChange = strcmp($purchaseData['quantity'],$purchaseNewData['quantity']);
				if($isChange > 0){
					//减少
					$quantity = $purchaseData['quantity']-$purchaseNewData['quantity'];
					$this->service('PositionInventory')->reduceInventory($purchaseData['symbol_identity'],$quantity);
					
				}
				
				if($isChange < 0){
					//增加
					$quantity = $purchaseNewData['quantity']-$purchaseData['quantity'];
					$this->service('PositionInventory')->addInventory($purchaseData['symbol_identity'],$quantity);
					
				}
			}
			
		}
	}
	
	public function fetchQuantity($accountId,$happenTime){
		
		$quantity = 0;
		$where = array(
			'account_identity'=>$accountId,
			'happen_date'=>$happenTime
		);
		
		$purchaseData = $this->model('PositionPurchase')->field('sum(quantity) as quantity')->where($where)->find();
		if($purchaseData){
			$quantity += $purchaseData['quantity'];
		}
		return $quantity;
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
	public function insert($purchaseData){
		
		$dateline = $this->getTime();
		$purchaseData['subscriber_identity'] = $this->session('uid');
		$purchaseData['dateline'] = $dateline;
		$purchaseData['lastupdate'] = $dateline;

		$purchaseData['sn'] = $this->get_sn();
		if(!$purchaseData['happen_date']){
            $purchaseData['happen_date'] = $dateline;
        }
		
		$purchaseId = $this->model('PositionPurchase')->data($purchaseData)->add();
		if($purchaseId){			
			$quantity = $this->fetchQuantity($purchaseData['account_identity'],$purchaseData['happen_date']);
			//$quantity += $purchaseData['quantity'];
			$this->service('PositionInventory')->addInventory($purchaseData['idtype'],$purchaseData['id'],$purchaseData['quantity']);
			$this->service('QuotationPosition')->account($purchaseData['account_identity'])->purchase($quantity)->period($purchaseData['happen_date'])->push();
		}
		return $purchaseId;
		
	}
}