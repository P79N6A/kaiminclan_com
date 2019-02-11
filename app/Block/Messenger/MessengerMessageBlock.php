<?php
class MessengerMessageBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$messageId = isset($param['messageId'])?$param['messageId']:0;
		$templateId = isset($param['templateId'])?$param['templateId']:0;
		
		$where = array();
		if($messageId){
			$where['identity'] = $messageId;
		}
		if($templateId){
			$where['template_identity'] = $templateId;
		}
		
		$listdata = $this->service('MessengerMessage')->getMessageList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}