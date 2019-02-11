<?php
class MessengerReceiveBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$receiveId = isset($param['receiveId'])?$param['receiveId']:0;
		$messageId = isset($param['messageId'])?$param['messageId']:0;
		
		$where = array();
		if($receiveId){
			$where['identity'] = $receiveId;
		}
		if($messageId){
			$where['message_identity'] = $messageId;
		}
		
		$listdata = $this->service('MessengerReceive')->getReceiveList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}