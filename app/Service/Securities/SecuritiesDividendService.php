<?php
/**
 *
 * 合作伙伴
 *
 * 新闻
 *
 */
class SecuritiesDividendService extends Service
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
	public function getDividendList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('SecuritiesDividend')->where($where)->count();
		if($count){
			$handle = $this->model('SecuritiesDividend')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$stockIds = array();
			foreach($listdata as $key=>$data){
				$stockIds[] = $data['stock_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>SecuritiesStockModel::getStatusTitle($data['status'])
				);
			}
			
			$stockData = $this->service('SecuritiesStock')->getStockInfo($stockIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['stock'] = isset($stockData[$data['stock_identity']])?$stockData[$data['stock_identity']]:array();
			}
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
	public function checkDividendTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('SecuritiesDividend')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $dividendId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getDividendInfo($dividendId,$field = '*'){
		
		$where = array(
			'identity'=>$dividendId
		);
		
		$dividendData = $this->model('SecuritiesDividend')->field($field)->where($where)->find();
		
		return $dividendData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $dividendId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeDividendId($dividendId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$dividendId
		);
		
		$dividendData = $this->model('SecuritiesDividend')->where($where)->find();
		if($dividendData){
			
			$output = $this->model('SecuritiesDividend')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $dividendId 模块ID
	 * @param $dividendNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($dividendNewData,$dividendId){
		$where = array(
			'identity'=>$dividendId
		);
		
		$dividendData = $this->model('SecuritiesDividend')->where($where)->find();
		if($dividendData){
			
			$dividendNewData['lastupdate'] = $this->getTime();
			$this->model('SecuritiesDividend')->data($dividendNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $dividendNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($dividendNewData){
		
		$dividendNewData['subscriber_identity'] =$this->session('uid');
		$dividendNewData['dateline'] = $this->getTime();
			
		$dividendNewData['lastupdate'] = $dividendNewData['dateline'];
		$this->model('SecuritiesDividend')->data($dividendNewData)->add();
	}
}