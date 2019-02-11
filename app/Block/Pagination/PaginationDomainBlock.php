<?php
class PaginationDomainBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$domainId = isset($param['domainId'])?$param['domainId']:0;
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($domainId){
			$where['identity'] = $domainId;
		}
		
		
		$listdata = $this->service('PaginationDomain')->getDomainList($where,$start,$perpage,$order);
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