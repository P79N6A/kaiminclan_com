<?php
class AttachmentServerBlock extends Block {
	public function getdata($param){
		
		$serverId = isset($param['serverId'])?$param['serverId']:0;
		$keyword = isset($param['kw'])?$param['kw']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$order = isset($param['order'])?$param['order']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($serverId){
			$where['identity'] = $serverId;
		}
		
		$listdata = $this->service('AttachmentServer')->getServerList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}