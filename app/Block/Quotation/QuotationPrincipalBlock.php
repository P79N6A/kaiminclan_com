<?php
class QuotationPrincipalBlock extends Block {
	public function getdata($param){
		
		$indicatrixId = isset($param['indicatrixId'])?$param['indicatrixId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		
		$where = array();
		if($indicatrixId){
			$where['identity'] = $indicatrixId;
		}
		
		$order = 'identity desc';
		
		$listdata = $this->service('QuotationPrincipal')->getPrincipalList($where,$start,$perpage,$order);
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