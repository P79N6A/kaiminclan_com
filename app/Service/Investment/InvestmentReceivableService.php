<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class InvestmentReceivableService extends Service
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
	public function getReceivableList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('InvestmentReceivable')->where($where)->count();
		if($count){
			$handle = $this->model('InvestmentReceivable')->where($where);
			if($start && $perpage){
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
	public function checkReceivableTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('InvestmentReceivable')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $receivableId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getReceivableInfo($receivableId,$field = '*'){
		
		$where = array(
			'identity'=>$receivableId
		);
		
		$receivableData = $this->model('InvestmentReceivable')->field($field)->where($where)->find();
		
		return $receivableData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $receivableId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeReceivableId($receivableId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$receivableId
		);
		
		$receivableData = $this->model('InvestmentReceivable')->where($where)->find();
		if($receivableData){
			
			$output = $this->model('InvestmentReceivable')->where($where)->delete();
			
			$this->service('PaginationItem')->removeReceivableIdAllItem($receivableId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $receivableId 模块ID
	 * @param $receivableNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($receivableNewData,$receivableId){
		$where = array(
			'identity'=>$receivableId
		);
		
		$receivableData = $this->model('InvestmentReceivable')->where($where)->find();
		if($receivableData){
			
			$receivableNewData['lastupdate'] = $this->getTime();
			$this->model('InvestmentReceivable')->data($receivableNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $receivableNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($receivableNewData,$multi = 0){
		if($multi){
			foreach($receivableNewData as $field=>$receivableList){
				foreach($receivableList as $key=>$receivable){
					$receivableNewData['subscriber_identity'][$key] =$this->session('uid');
					$receivableNewData['dateline'][$key] = $this->getTime();
						
					$receivableNewData['lastupdate'][$key] = $receivableNewData['dateline'][$key];
				}
				break;
			}
			$this->model('InvestmentReceivable')->data($receivableNewData)->addMulti();
		}else{
			$receivableNewData['subscriber_identity'] =$this->session('uid');
			$receivableNewData['dateline'] = $this->getTime();
				
			$receivableNewData['lastupdate'] = $receivableNewData['dateline'];
			$this->model('InvestmentReceivable')->data($receivableNewData)->add();
		}
	}
}