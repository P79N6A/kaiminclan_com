<?php
class PaginationCatalogueBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$catalogueId = isset($param['catalogueId'])?$param['catalogueId']:0;
        $parentId = isset($param['parentId'])?$param['parentId']:0;
		$platformId = isset($param['platformId'])?$param['platformId']:0;
		$domainId = isset($param['domainId'])?$param['domainId']:0;
		
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$start = isset($param['start'])?$param['start']:1;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($catalogueId){
			$where['identity'] = $catalogueId;
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

		$where['catalogue_identity'] = $parentId;

		$listdata = $this->service('PaginationCatalogue')->getCatalogueList($where,$start,$perpage,$order);
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