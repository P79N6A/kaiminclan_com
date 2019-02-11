<?php
class MessengerTemplateBlock extends Block {
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$templateId = isset($param['templateId'])?$param['templateId']:0;
		
		$where = array();
		if($templateId){
			$where['identity'] = $templateId;
		}
		
		$listdata = $this->service('MessengerTemplate')->getTemplateList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}