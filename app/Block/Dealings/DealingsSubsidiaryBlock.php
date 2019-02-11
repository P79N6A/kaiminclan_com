<?php
class DealingsSubsidiaryBlock extends Block {
	public function getdata($param){
		
		$subsidiaryId = isset($param['subsidiaryId'])?$param['subsidiaryId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$order = 'identity desc';
		$where = array();
		
		if($subsidiaryId){
			$where['identity'] = $subsidiaryId;
		}
		$listdata = $this->service('DealingsSubsidiary')->getSubsidiaryList($where,$start,$perpage,$order);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}