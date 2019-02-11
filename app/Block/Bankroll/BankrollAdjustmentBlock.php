<?php
/***
 *
 * 调账
 *
 */
class BankrollAdjustmentBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:1;
		$start = isset($param['start'])?$param['start']:1;
		$adjustmentId = isset($param['adjustmentId'])?$param['adjustmentId']:0;
		
		$where = array();
		if($adjustmentId){
			$where['identity'] = $adjustmentId;
		}
				
		$listdata = $this->service('BankrollAdjustment')->getAdjustmentList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}