<?php
/***
 *
 * 设备模块
 *
 */
class PassagewayChannelBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$channelId = isset($param['channelId'])?$param['channelId']:0;
		
		$where = array();
		if($channelId){
			$where['identity'] = $channelId;
		}
		
		$listdata = $this->service('PassagewayChannel')->getChannelList($where,$start,$perpage);
		if($perpage < 1 && $perpage > -1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}