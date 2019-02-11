<?php
/***
 *
 * 资金流水
 *
 */
class BankrollSubsidiaryBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$start = isset($param['start'])?$param['start']:0;
        $accountId = isset($param['accountId'])?$param['accountId']:0;
        $mode = isset($param['mode'])?$param['mode']:0;


        $where = array();
		if($mode){
			if($accountId){
				$where['account_identity'] = $accountId;
			}else{
				$where['subscriber_identity'] = $this->session('uid');
			}
		}
				
		$listdata = $this->service('BankrollSubsidiary')->getSubsidiaryList($where,$start,$perpage);
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}