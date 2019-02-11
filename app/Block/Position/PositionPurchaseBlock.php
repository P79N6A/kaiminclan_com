<?php
/***
 *
 * 开仓
 *
 */
class PositionPurchaseBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$start = isset($param['start'])?$param['start']:0;
		$purchaseId = isset($param['purchaseId'])?$param['purchaseId']:0;
        $accountId = isset($param['accountId'])?$param['accountId']:0;
        $startDate = isset($param['startDate'])?$param['startDate']:-1;
        $stopDate = isset($param['stopDate'])?$param['stopDate']:-1;
		
		$where = array();
		if($purchaseId){
			$where['identity'] = $purchaseId;
		}
        if($accountId){
            $where['account_identity'] = $accountId;
        }else{
            $where['subscriber_identity'] = $this->session('uid');
        }
		
		if($startDate != -1){
			if(!$startDate){
				$startDate = strtotime('-7 day');
			}else{
				$startDate = strtotime($startDate);
			}
			$filterData['startDate'] = $startDate;
		}
		
		if($stopDate != -1){
			if(!$stopDate){
				$stopDate = $this->getTime();
			}else{
				$stopDate = strtotime($stopDate);
			}
			$filterData['stopDate'] = $stopDate;
		}
		
		$where['dateline'] = array('BETWEEN',array($startDate,$stopDate));
		
				
		$listdata = $this->service('PositionPurchase')->getPurchaseList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start,'filter'=>$filterData);
	}
}