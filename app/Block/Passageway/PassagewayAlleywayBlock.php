<?php
/***
 *
 * 足迹
 *
 */
class PassagewayAlleywayBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$alleywayId = isset($param['alleywayId'])?$param['alleywayId']:0;
		
		$where = array();
		if($alleywayId){
			$where['identity'] = $alleywayId;
		}
		
		$listdata = $this->service('PassagewayAlleyway')->getAlleywayList($where,$start,$perpage);
		if($perpage < 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}