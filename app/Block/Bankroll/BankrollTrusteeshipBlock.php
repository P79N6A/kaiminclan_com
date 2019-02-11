<?php
/***
 *
 * 资金流水
 *
 */
class BankrollTrusteeshipBlock  extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$trusteeshipId = isset($param['trusteeshipId'])?$param['trusteeshipId']:0;
		$accountId = isset($param['accountId'])?$param['accountId']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		$order = isset($param['order'])?$param['order']:0;
		
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($trusteeshipId){
			$where['identity'] = $trusteeshipId;
		}
		if($accountId){
			$where['account_identity'] = $accountId;
		}
		
		switch($order){
			case 1:
				$order = 'lastupdate DESC';
				break;
			default:
				$order = 'identity DESC';
				break;
		}
				
		$listdata = $this->service('BankrollTrusteeship')->getTrusteeshipList($where,$start,$perpage,$order);
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}