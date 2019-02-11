<?php
class PaginationPlatformBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$domainId = isset($param['domainId'])?$param['domainId']:0;
		
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if(!$domainId && $mode){
			$domainId = $this->service('PaginationDomain')->getDomainIdByCode(__DOMAIN__);
		}
		if($domainId){
			$where['domain_identity'] = $domainId;
		}
		if($platformId){
			$where['identity'] = $platformId;
		}
		
		$listdata = $this->service('PaginationPlatform')->getPlatformList($where,$start,$perpage,$order);
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