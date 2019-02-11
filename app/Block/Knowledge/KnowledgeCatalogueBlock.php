<?php
/***
 *
 * 目录
 * 知识库
 *
 */
class KnowledgeCatalogueBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$start = isset($param['start'])?$param['start']:0;
		$catalogueId = isset($param['catalogueId'])?$param['catalogueId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		
		$where = array();
		if($catalogueId){
			$where['identity'] = $catalogueId;
		}
		if($parentId != -1){
			$where['catalogue_identity'] = $parentId;
		}
				
		$listdata = $this->service('KnowledgeCatalogue')->getCatalogueList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}