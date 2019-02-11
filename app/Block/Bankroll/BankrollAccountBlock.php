<?php
/***
 *
 * 账户
 *
 */
class BankrollAccountBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$accountId = isset($param['accountId'])?$param['accountId']:0;
		
		$where = array();
		if($accountId){
			$where['identity'] = $accountId;
		}
				
		$listdata = $this->service('BankrollAccount')->getAccountList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}