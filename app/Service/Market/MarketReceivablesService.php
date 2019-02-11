<?php
 // +----------------------------------------------------------------------
// | 桐鹰科技
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2018 http://support.shouzhangyushe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 简启敏 <kaimin.clan@gmail.com>
// +----------------------------------------------------------------------
/**
 * 付款
 *
 * 销售
 */
class MarketReceivablesService extends Service {
		
	/**
	 *
	 * 获取付款流水号
	 *
	 *
	 * @reutrn array;
	 */
	public function getReceivablesCode(){
		
		$where = array();
		
		$where['dateline'] = array('GT',strtotime(date('Y-m-d',strtotime('-1 day')))+(60*60*16-1));
		$count = $this->model('MarketReceivables')->where()->count();
		
		return date('Ymd').sprintf('%06s', $count+1);
	}
	/**
	 *
	 * 付款信息
	 *
	 * @param $field 付款字段
	 * @param $status 付款状态
	 *
	 * @reutrn array;
	 */
	public function getAllreceivablesList($field = 'identity,title',$status = MarketReceivablesModel::SUPPLIER_receivablesING_STATUS_ENABLE){
		
		$where = array(
			'status'=>$status
		);
		
		$receivablesData = $this->model('MarketReceivables')->field($field)->where($where)->select();
		
		return $receivablesData;
	}
	
	/**
	 *
	 * 付款信息
	 *
	 * @param $receivablesId 付款ID
	 *
	 * @reutrn array;
	 */
	public function getReceivablesInfo($receivablesId){
		
		$receivablesData = array();
		
		$where = array(
			'identity'=>$receivablesId
		);
		
		$receivablesList = $this->model('MarketReceivables')->where($where)->select();
		if($receivablesList){
			
		}
		
		if(!is_array($receivablesId)){
			$receivablesData = current($receivablesList);
		}else{
			$receivablesData = $receivablesList;
		}
		
		return $receivablesData;
	}
	/**
	 *
	 * 检测申请状态
	 *
	 * @param $orderddId 订单ID
	 * @param $shoppingId 订购ID
	 *
	 * @reutrn int;
	 */
	public function checkReceivables($orderddId,$shoppingId = 0){
		$where = array(
			'orderdd_identity'=>$orderddId
		);
		$where['shopping_identity'] = $shoppingId;
		
		return $this->model('MarketReceivables')->where($where)->find();
		
	}
	
	/**
	 *
	 * 删除付款
	 *
	 * @param $receivablesId 付款ID
	 *
	 * @reutrn int;
	 */
	public function removeReceivablesId($receivablesId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$receivablesId
		);
		
		$receivablesData = $this->model('MarketReceivables')->where($where)->count();
		if($receivablesData){
						
			$output = $this->model('MarketReceivables')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 付款修改
	 *
	 * @param $receivablesId 付款ID
	 * @param $receivablesNewData 付款数据
	 *
	 * @reutrn int;
	 */
	public function update($receivablesNewData,$receivablesId){
		$where = array(
			'identity'=>$receivablesId
		);
		
		$receivablesData = $this->model('MarketReceivables')->where($where)->find();
		if($receivablesData){
			
			$receivablesNewData['lastupdate'] = $this->getTime();
			$result = $this->model('MarketReceivables')->data($receivablesNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新付款
	 *
	 * @param $receivablesNewData 付款信息
	 *
	 * @reutrn int;
	 */
	public function insert($receivablesNewData){
		$receivablesNewData['subscriber_identity'] =$this->session('uid');		
		$receivablesNewData['dateline'] = $this->getTime();
			
		$receivablesNewData['lastupdate'] = $receivablesNewData['dateline'];
		$receivablesId = $this->model('MarketReceivables')->data($receivablesNewData)->add();
		return $receivablesId;
		
	}
}
?>