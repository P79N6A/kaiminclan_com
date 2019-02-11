<?php
/***
 *
 * 转出
 *
 */
class BankrollExpensesBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$expensesId = isset($param['expensesId'])?$param['expensesId']:0;
		
		$where = array();
		if($expensesId){
			$where['identity'] = $expensesId;
		}
				
		$listdata = $this->service('BankrollExpenses')->getExpensesList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}