<?php
/**
 *
 * 库存
 *
 * 头寸
 *
 */
class  PositionInventoryService extends Service {
	
	
	
	/**
	 *
	 * 头寸列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getInventoryList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PositionInventory')->where($where)->count();
		if($count){
			$inventoryHandle = $this->model('PositionInventory')->where($where)->orderby($orderby);
			if($start && $perpage){
				$inventoryHandle = $inventoryHandle->limit($start,$perpage,$count);
			}
			$listdata = $inventoryHandle->select();
			
			$idTypeData = array();
			foreach($listdata as $key=>$purchase){
				$idTypeData[$purchase['idtype']][] = $purchase['id'];
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
	 * 头寸信息
	 *
	 * @param $inventoryIds 头寸ID
	 *
	 * @reutrn int;
	 */
	public function getInventoryInfo($inventoryIds){
		$inventoryData = array();
		
		$where = array(
			'identity'=>$inventoryIds
		);
		
		$inventoryList = $this->model('PositionInventory')->where($where)->select();
		if($inventoryList){
			
			if(is_array($inventoryIds)){
				$inventoryData = $inventoryList;
			}else{
				$inventoryData = current($inventoryList);
			}
			
			
		}
		
		
		return $inventoryData;
	}
	
	
		
	/**
	 *
	 * 删除头寸
	 *
	 * @param $inventoryId 头寸ID
	 *
	 * @reutrn int;
	 */
	public function removeInventoryId($inventoryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$inventoryId
		);
		
		$inventoryData = $this->model('PositionInventory')->where($where)->count();
		if($inventoryData){
			
			$output = $this->model('PositionInventory')->where($where)->delete();
		}
		
		return $output;
	}
	
	public function addInventory($idtype,$symbolIds,$quantity){
		
		if(!is_numeric($quantity) && $quantity < 0){
			return -1;
		}
		
		$inventoryData = array(
			'id'=>$symbolIds,
            'idtype'=>$idtype,
			'quantity'=>$quantity
		);
			
		$where = array();
		$where['id'] = intval($symbolIds);
		$oldInventoryData = $this->model('PositionInventory')->where($where)->find();
		if(!$oldInventoryData){
			$this->insert($inventoryData);
		}else{
			$inventoryData['quantity'] = $inventoryData['quantity']+$oldInventoryData['quantity'];
		}
		
		$this->update($inventoryData,$oldInventoryData['identity']);
	}
	
	public function reduceInventory($idtype,$symbolIds,$quantity){
		
		if(!is_numeric($quantity) && $quantity < 0){
			return -1;
		}
		
		$inventoryData = array(
			'quantity'=>$quantity
		);
		$where = array();
		$where['id'] = intval($symbolIds);
		$where['idtype'] = $idtype;
		$oldInventoryData = $this->model('PositionInventory')->where($where)->find();
		if(!$oldInventoryData){
			return -2;
		}
		$inventoryData['quantity'] = $inventoryData['quantity']-$oldInventoryData['quantity'];
		
		$this->update($inventoryData,$oldInventoryData['identity']);
	}
	
	/**
	 *
	 * 头寸修改
	 *
	 * @param $inventoryId 头寸ID
	 * @param $inventoryNewData 头寸数据
	 *
	 * @reutrn int;
	 */
	public function update($inventoryNewData,$inventoryId){
		$where = array(
			'identity'=>$inventoryId
		);
		$inventoryNewData['lastupdate'] = $this->getTime();
		$this->model('PositionInventory')->data($inventoryNewData)->where($where)->save();
			
	}
	
	/**
	 *
	 * 新头寸
	 *
	 * @param $id 头寸信息
	 * @param $idtype 头寸信息
	 *
	 * @reutrn int;
	 */
	public function insert($inventoryData){
		$dateline = $this->getTime();
		$inventoryData['subscriber_identity'] = $this->session('uid');
		$inventoryData['dateline'] = $dateline;
		$inventoryData['lastupdate'] = $dateline;
			
		
		return $this->model('PositionInventory')->data($inventoryData)->add();
		
	}
}