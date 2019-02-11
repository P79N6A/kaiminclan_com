<?php
class AttachmentCatalogBlock extends Block {
	public function getdata($param){
		
		$catalogId = isset($param['catalogId'])?$param['catalogId']:0;
		$keyword = isset($param['kw'])?$param['kw']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$order = isset($param['order'])?$param['order']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($catalogId){
			$where['identity'] = $catalogId;
		}
		
		if($mode){
			$where['subscriber_identity'] = $this->session('uid');
		}
		
		$listdata = $this->service('AttachmentCatalog')->getCatalogList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}