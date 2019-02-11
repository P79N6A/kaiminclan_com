<?php
/***
 *
 * 足迹
 *
 */
class InterviewVisitorBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		
		$where = array();
		
		
		$listdata = $this->service('InterviewVisitor')->getVisitorList($where,$start,$perpage);
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}