<?php
/**
 *
 * 应付款
 *
 * 页面
 *
 */
class PermanentPayableService extends Service
{
	
	/**
	 *
	 * 应付款信息
	 *
	 * @param $field 应付款字段
	 * @param $status 应付款状态
	 *
	 * @reutrn array;
	 */
	public function getPayableList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PermanentPayable')->where($where)->count();
		if($count){
			$handle = $this->model('PermanentPayable')->where($where);
			$start = intval($start);
			$perpage = intval($perpage);
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
	public function checkPayableTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('PermanentPayable')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 应付款信息
	 *
	 * @param $payableId 应付款ID
	 *
	 * @reutrn array;
	 */
	public function getPayableInfo($payableId,$field = '*'){
		
		$where = array(
			'identity'=>$payableId
		);
		
		$payableData = $this->model('PermanentPayable')->field($field)->where($where)->find();
		
		return $payableData;
	}
	
	/**
	 *
	 * 删除应付款
	 *
	 * @param $payableId 应付款ID
	 *
	 * @reutrn int;
	 */
	public function removePayableId($payableId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$payableId
		);
		
		$payableData = $this->model('PermanentPayable')->where($where)->find();
		if($payableData){
			
			$output = $this->model('PermanentPayable')->where($where)->delete();
			
			$this->service('PaginationItem')->removePayableIdAllItem($payableId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 应付款修改
	 *
	 * @param $payableId 应付款ID
	 * @param $payableNewData 应付款数据
	 *
	 * @reutrn int;
	 */
	public function update($payableNewData,$payableId){
		$where = array(
			'identity'=>$payableId
		);
		
		$payableData = $this->model('PermanentPayable')->where($where)->find();
		if($payableData){
			
			$payableNewData['lastupdate'] = $this->getTime();
			$this->model('PermanentPayable')->data($payableNewData)->where($where)->save();
			if(isset($payableNewData['expenses_date']) && isset($payableNewData['expenses_identity'])){
				$this->service('PermanentCredit')->adjustAmount($payableData['id'],$payableData['amount']);
			}
		}
	}
	
	/**
	 *
	 * 关闭应付款
	 *
	 * @param $payableId 应付款ID
	 * @param $expensesId 支出ID
	 *
	 * @reutrn int;
	 */
	public function closePayable($payableId,$expensesId){
		$payableNewData = array(
			'payment_time'=>$this->getTime(),
			'payment_expenses_identity'=>$expensesId
		);
		
		return $this->update($payableNewData,$payableId);
	}
	
	/**
	 *
	 * 新应付款
	 *
	 * @param $payableNewData 应付款数据
	 *
	 * @reutrn int;
	 */
	public function insert($payableNewData,$multi = 0){
		if($multi){
			foreach($payableNewData as $field=>$payableList){
				foreach($payableList as $key=>$payable){
					$payableNewData['subscriber_identity'][$key] =$this->session('uid');
					$payableNewData['dateline'][$key] = $this->getTime();
						
					$payableNewData['lastupdate'][$key] = $payableNewData['dateline'][$key];
				}
				break;
			}
			$this->model('PermanentPayable')->data($payableNewData)->addMulti();
		}else{
			$payableNewData['subscriber_identity'] =$this->session('uid');
			$payableNewData['dateline'] = $this->getTime();
				
			$payableNewData['lastupdate'] = $payableNewData['dateline'];
			$this->model('PermanentPayable')->data($payableNewData)->add();
		}
	}
}