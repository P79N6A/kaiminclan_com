<?php
/**
 *
 * 信号
 *
 * 统计分析
 *
 */
class QuotationSignalService extends Service
{
	
	/**
	 *
	 * 信号信息
	 *
	 * @param $field 信号字段
	 * @param $status 信号状态
	 *
	 * @reutrn array;
	 */
	public function getSignalList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('QuotationSignal')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationSignal')->where($where);
			if($order){
				$handle->orderby($order);
			}
			
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
}