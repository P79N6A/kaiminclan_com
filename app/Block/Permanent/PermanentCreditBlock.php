<?php
class PermanentCreditBlock extends Block {
	public function getdata($param){
		
		$creditId = isset($param['creditId'])?$param['creditId']:0;
		$channelId = isset($param['channelId'])?$param['channelId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($creditId){
			$where['identity'] = $creditId;
		}
		if($channelId){
			$where['channel_identity'] = $channelId;
		}
		
		$listdata = $this->service('PermanentCredit')->getCreditList($where,$start,$perpage,$order);
		if($perpage == 1){
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