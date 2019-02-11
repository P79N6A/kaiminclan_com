<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class QuotationInvaluableService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getInvaluableList($where,$start,$perpage,$principalId = 0){
		
		$initPerPage = $perpage;
		$frameId = array();
		$frameworkData = $this->service('QuotationFramework')->fetchFrameworkIdByPrincipalId($principalId);
		if($frameworkData){
			foreach($frameworkData as $key=>$framework){
				$frameId[] = $framework['identity'];
			}
			$perpage = $perpage*count($frameId);
		}
		$where['framework_identity'] = $frameId;
		$count = $this->model('QuotationInvaluable')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationInvaluable')->where($where)->order('cycle DESC');
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			
			$symbolIds = $listTemp = array();
			foreach($listdata as $key=>$data){
				$curTime = substr($data['cycle'],0,8);
				$listTemp[$data['id']][$curTime][$frameworkData[$data['framework_identity']]['code']] = $data['data']; 
				$symbolIds[] = $data['id'];
			}
			
			$symbolData = $this->service('SecuritiesStock')->getStockInfo($symbolIds);
			foreach($listTemp as $id=>$data){
				foreach($data as $time=>$val){
					$val['today'] = $time;
					$val['id'] = $id;
					$list[] = $val;
				}
			}
			foreach($list as $key=>$data){
				$list[$key]['symbol'] = isset($symbolData[$data['id']])?$symbolData[$data['id']]:array();
			}
			
		}
		return array('total'=>$count,'list'=>$list);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkInvaluableTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('QuotationInvaluable')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $invaluableId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getInvaluableInfo($invaluableId,$field = '*'){
		
		$where = array(
			'identity'=>$invaluableId
		);
		
		$invaluableData = $this->model('QuotationInvaluable')->field($field)->where($where)->find();
		
		return $invaluableData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $invaluableId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeInvaluableId($invaluableId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$invaluableId
		);
		
		$invaluableData = $this->model('QuotationInvaluable')->where($where)->find();
		if($invaluableData){
			
			$output = $this->model('QuotationInvaluable')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $invaluableId 模块ID
	 * @param $invaluableNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($invaluableNewData,$invaluableId){
		$where = array(
			'identity'=>$invaluableId
		);
		
		$invaluableData = $this->model('QuotationInvaluable')->where($where)->find();
		if($invaluableData){
			
			$invaluableNewData['lastupdate'] = $this->getTime();
			$this->model('QuotationInvaluable')->data($invaluableNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $invaluableNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($invaluableNewData){
		
		$invaluableNewData['subscriber_identity'] =$this->session('uid');
		$invaluableNewData['dateline'] = $this->getTime();
			
		$invaluableNewData['lastupdate'] = $invaluableNewData['dateline'];
		$this->model('QuotationInvaluable')->data($invaluableNewData)->add();
	}
}