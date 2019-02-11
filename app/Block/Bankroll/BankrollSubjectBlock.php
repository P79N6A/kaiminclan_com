<?php
/***
 *
 * 资金流水
 *
 */
class BankrollSubjectBlock  extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
				
		$listdata = $this->service('BankrollSubject')->getSubjectList($where,$start,$perpage);
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}