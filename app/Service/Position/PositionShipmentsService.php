<?php
/**
 *
 * 平仓
 *
 * 头寸
 *
 */
class  PositionShipmentsService extends Service {
	
	
	
	/**
	 *
	 * 平仓列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getShipmentsList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PositionShipments')->where($where)->count();
		if($count){
			$shipmentsHandle = $this->model('PositionShipments')->where($where)->orderby($orderby);
			if($start && $perpage){
				$shipmentsHandle = $shipmentsHandle->limit($start,$perpage,$count);
			}
			$listdata = $shipmentsHandle->select();
			
			$idTypeData = array();
			foreach($listdata as $key=>$purchase){
				$idTypeData[$purchase['idtype']][] = $purchase['id'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>PositionShipmentsModel::getStatusTitle($data['status'])
				);
			}
			
			foreach($idTypeData as $idtype=>$ids){
				switch($idtype){
					case PositionShipmentsModel::POSITION_SHIPMENTS_IDTYPE_STOCK:
						$targetData = $this->service('SecuritiesStock')->getStockInfo($ids,'identity,title');
						break;
					case PositionShipmentsModel::POSITION_SHIPMENTS_IDTYPE_FOREX:
						$targetData = $this->service('ForeignContact')->getContactInfo($ids,'identity,title');
						break;
					case PositionShipmentsModel::POSITION_SHIPMENTS_IDTYPE_BOND:
						$targetData =$this->service('DebentureBond')->getBondInfo($ids,'identity,title');
						break;
					case PositionShipmentsModel::POSITION_SHIPMENTS_IDTYPE_FUTURES:
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
	 * 平仓信息
	 *
	 * @param $shipmentsIds 平仓ID
	 *
	 * @reutrn int;
	 */
	public function getShipmentsInfo($shipmentsIds){
		$shipmentsData = array();
		
		$where = array(
			'identity'=>$shipmentsIds
		);
		
		$shipmentsList = $this->model('PositionShipments')->where($where)->select();
		if($shipmentsList){
			
			if(is_array($shipmentsIds)){
				$shipmentsData = $shipmentsList;
			}else{
				$shipmentsData = current($shipmentsList);
			}
			
			
		}
		
		
		return $shipmentsData;
	}
	
	
		
	/**
	 *
	 * 删除平仓
	 *
	 * @param $shipmentsId 平仓ID
	 *
	 * @reutrn int;
	 */
	public function removeShipmentsId($shipmentsId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$shipmentsId
		);
		
		$shipmentsData = $this->model('PositionShipments')->where($where)->count();
		if($shipmentsData){
			
			$output = $this->model('PositionShipments')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测平仓
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkShipments($purchase_identity){
		$purchaseId = array();		
		$where = array(
			'purchase_identity'=>intval($purchase_identity),
		);
		
		
		return $this->model('PositionShipments')->where($where)->count();
	}
	
	
	/**
	 *
	 * 平仓修改
	 *
	 * @param $shipmentsId 平仓ID
	 * @param $shipmentsNewData 平仓数据
	 *
	 * @reutrn int;
	 */
	public function update($shipmentsNewData,$shipmentsId){
		$where = array(
			'identity'=>$shipmentsId
		);
		
		$shipmentsData = $this->model('PositionShipments')->where($where)->find();
		if($shipmentsData){
			
			
			$shipmentsNewData['lastupdate'] = $this->getTime();
			$this->model('PositionShipments')->data($shipmentsNewData)->where($where)->save();
			
			$isChange = strcmp($shipmentsData['quantity'],$shipmentsNewData['quantity']);
			if(strcmp($isChange,0) !== 0){
				$purchaseData = $this->service('PositionPurchase')->getPurchaseInfo($shipmentsData['purchase_identity']);
				if($isChange > 0){
					//减少
					$quantity = $shipmentsData['quantity']-$shipmentsNewData['quantity'];
					$this->service('PositionInventory')->addInventory($purchaseData['symbol_identity'],$quantity);
					
				}
				
				if($isChange < 0){
					//增加
					$quantity = $shipmentsNewData['quantity']-$shipmentsData['quantity'];
					
					$this->service('PositionInventory')->reduceInventory($purchaseData['symbol_identity'],$quantity);
					
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
		
		$purchaseData = $this->model('PositionShipments')->field('sum(quantity) as quantity')->where($where)->find();
		if($purchaseData){
			$quantity += $purchaseData['quantity'];
		}
		return $quantity;
	}
	
	public function fetchProfit($accountId,$happenTime){
		$profit = 0;
		$where = array(
			'account_identity'=>$accountId,
			'happen_date'=>$happenTime
		);
		
		$shipmentsData = $this->model('PositionShipments')->field('sum(profit) as profit')->where($where)->find();
		if($shipmentsData){
			$profit += $shipmentsData['profit'];
		}
		return $profit;
	}
	
	/**
	 *
	 * 新平仓
	 *
	 * @param $id 平仓信息
	 * @param $idtype 平仓信息
	 *
	 * @reutrn int;
	 */
	public function insert($shipmentsData){
		$dateline = $this->getTime();
		$shipmentsData['subscriber_identity'] = $this->session('uid');
		$shipmentsData['dateline'] = $dateline;
		$shipmentsData['lastupdate'] = $dateline;
		$shipmentsData['sn'] = $this->get_sn();
        if(!$shipmentsData['happen_date']){
            $shipmentsData['happen_date'] = $dateline;
        }
		
		$shipmentsId = $this->model('PositionShipments')->data($shipmentsData)->add();
		
		if($shipmentsId){
			
			$purchaseData = $this->service('PositionPurchase')->getPurchaseInfo($shipmentsData['purchase_identity']);
			$this->service('PositionPurchase')->close($shipmentsData['purchase_identity']);
				
			$this->service('PositionInventory')->reduceInventory($purchaseData['idtype'],$purchaseData['id'],$shipmentsData['quantity']);
			if($shipmentsData['profit'] < 0){
				$this->service('BankrollSubsidiary')->newLeave($shipmentsData['account_identity'],$shipmentsData['profit']);
			}else{
				$this->service('BankrollSubsidiary')->newIncome($shipmentsData['account_identity'],$shipmentsData['profit']);
			}
			
			$quantity = $this->fetchQuantity($shipmentsData['account_identity'],$shipmentsData['happen_date']);
			//$quantity += $shipmentsData['quantity'];
			$profit = $this->fetchQuantity($shipmentsData['account_identity'],$shipmentsData['happen_date']);
			//$profit += $shipmentsData['profit'];
			$this->service('QuotationPosition')->account($shipmentsData['account_identity'])->shipments($quantity)->profit($profit)->period($shipmentsData['happen_date'])->push();
		}
		
		return $shipmentsId;
		
	}
}