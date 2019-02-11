<?php
/***
 *
 * 经纪
 *
 */
class IntercalateBrokerBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$brokerId = isset($param['brokerId'])?$param['brokerId']:0;
		$industryId = isset($param['industryId'])?$param['industryId']:0;
		
		$where = array();
		if($brokerId){
			$where['identity'] = $brokerId;
		}
		if($industryId){
			$where['industry_identity'] = $industryId;
		}
				
		$listdata = $this->service('IntercalateBroker')->getBrokerList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}