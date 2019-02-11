<?php
class PaginationPageBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$pageId = isset($param['pageId'])?$param['pageId']:0;
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$domainId = isset($param['domainId'])?$param['domainId']:0;
		$catalogueId = isset($param['catalogueId'])?$param['catalogueId']:0;
		
		$perpage = isset($param['perpage'])?$param['perpage']:1;
		$start = isset($param['start'])?$param['start']:1;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'catalogue_identity DESC,identity desc';
		$where = array();
		if($pageId){
			$where['identity'] = $pageId;
		}
		if(!$domainId && $mode){
			$domainId = $this->service('PaginationDomain')->getDomainIdByCode(__DOMAIN__);
		}
		if($domainId){
			$where['domain_identity'] = $domainId;
		}
		
		if($platformId){
			$where['platform_identity'] = $platformId;
		}
		if($catalogueId){
			$where['catalogue_identity'] = $catalogueId;
		}
		$listdata = $this->service('PaginationPage')->getPageList($where,$start,$perpage,$order);
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