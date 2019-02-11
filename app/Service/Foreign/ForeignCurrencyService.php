<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class ForeignCurrencyService extends Service
{
	
	/**
	 *
	 * 货币信息
	 *
	 * @param $field 货币字段
	 * @param $status 货币状态
	 *
	 * @reutrn array;
	 */
	public function getCurrencyList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ForeignCurrency')->where($where)->count();
		if($count){
			$handle = $this->model('ForeignCurrency')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkCurrencyTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('ForeignCurrency')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 货币信息
	 *
	 * @param $currencyId 货币ID
	 *
	 * @reutrn array;
	 */
	public function getCurrencyInfo($currencyId,$field = '*'){
		$currencyData = array();
		
		if(!is_array($currencyId)){
			$currencyId = array($currencyId);
		}
		
		$currencyId = array_filter(array_map('intval',$currencyId));
		
		if(!empty($currencyId)){
			
			$where = array(
				'identity'=>$currencyId
			);
			
			$currencyData = $this->model('ForeignCurrency')->field($field)->where($where)->select();
		}
		return $currencyData;
	}
	
	/**
	 *
	 * 删除货币
	 *
	 * @param $currencyId 货币ID
	 *
	 * @reutrn int;
	 */
	public function removeCurrencyId($currencyId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$currencyId
		);
		
		$currencyData = $this->model('ForeignCurrency')->where($where)->find();
		if($currencyData){
			
			$output = $this->model('ForeignCurrency')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 货币修改
	 *
	 * @param $currencyId 货币ID
	 * @param $currencyNewData 货币数据
	 *
	 * @reutrn int;
	 */
	public function update($currencyNewData,$currencyId){
		$where = array(
			'identity'=>$currencyId
		);
		
		$currencyData = $this->model('ForeignCurrency')->where($where)->find();
		if($currencyData){
			
			$currencyNewData['lastupdate'] = $this->getTime();
			$this->model('ForeignCurrency')->data($currencyNewData)->where($where)->save();
            $this->service('PropertyCapital')->pushCurrencyCapital($currencyId,$currencyNewData['title']);
		}
	}
	
	/**
	 *
	 * 新货币
	 *
	 * @param $currencyNewData 货币数据
	 *
	 * @reutrn int;
	 */
	public function insert($currencyNewData){
		
		$currencyNewData['subscriber_identity'] =$this->session('uid');
		$currencyNewData['dateline'] = $this->getTime();
		$currencyNewData['sn'] = $this->get_sn();
		
		if(isset($currencyNewData['code'])){
			$currencyNewData['code'] = strtoupper($currencyNewData['code']);
		}
		
		$currencyNewData['lastupdate'] = $currencyNewData['dateline'];
		$currencyId = $this->model('ForeignCurrency')->data($currencyNewData)->add();
        if($currencyId){
            $this->service('PropertyCapital')->pushCurrencyCapital($currencyId,$currencyNewData['title']);
        }
		return $currencyId;
	}
}