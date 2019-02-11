<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class InvestmentCatalogService extends Service
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
	public function getCatalogList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('InvestmentCatalog')->where($where)->count();
		if($count){
			$handle = $this->model('InvestmentCatalog')->where($where);
			if($perpage > 0){
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
	public function checkCatalogTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('InvestmentCatalog')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $expensesId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogInfo($expensesId,$field = '*'){
		
		$where = array(
			'identity'=>$expensesId
		);
		
		$expensesData = $this->model('InvestmentCatalog')->field($field)->where($where)->select();
		
		return $expensesData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $expensesId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogId($expensesId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$expensesId
		);
		
		$expensesData = $this->model('InvestmentCatalog')->where($where)->find();
		if($expensesData){
			
			$output = $this->model('InvestmentCatalog')->where($where)->delete();
			
			$this->service('PaginationItem')->removeCatalogIdAllItem($expensesId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $expensesId 模块ID
	 * @param $expensesNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($expensesNewData,$expensesId){
		$where = array(
			'identity'=>$expensesId
		);
		
		$expensesData = $this->model('InvestmentCatalog')->where($where)->find();
		if($expensesData){
			
			$expensesNewData['lastupdate'] = $this->getTime();
			$this->model('InvestmentCatalog')->data($expensesNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $expensesNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($expensesNewData){
		
		$expensesNewData['subscriber_identity'] =$this->session('uid');
		$expensesNewData['dateline'] = $this->getTime();
			
		$expensesNewData['lastupdate'] = $expensesNewData['dateline'];
		$this->model('InvestmentCatalog')->data($expensesNewData)->add();
	}
}