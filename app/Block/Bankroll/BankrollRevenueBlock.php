<?php
/***
 *
 * 转入
 *
 */
class BankrollRevenueBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$revenueId = isset($param['revenueId'])?$param['revenueId']:0;
		
		$where = array();
		if($revenueId){
			$where['identity'] = $revenueId;
		}
				
		$listdata = $this->service('BankrollRevenue')->getRevenueList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}