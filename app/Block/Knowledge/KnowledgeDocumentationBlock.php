<?php
/***
 *
 * 目录
 * 知识库
 *
 */
class KnowledgeDocumentationBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$documentationId = isset($param['documentationId'])?$param['documentationId']:0;
		
		$where = array();
		if($documentationId){
			$where['identity'] = $documentationId;
		}
				
		$listdata = $this->service('KnowledgeDocumentation')->getDocumentationList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}