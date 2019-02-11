<?php
/***
 *
 * 交易所
 *
 */
class IntercalateExchangeBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$exchangeId = isset($param['exchangeId'])?$param['exchangeId']:0;
		
		$where = array();
		if($exchangeId){
			$where['identity'] = $exchangeId;
		}
				
		$listdata = $this->service('IntercalateExchange')->getExchangeList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}