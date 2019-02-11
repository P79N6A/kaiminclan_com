<?php
/**
 *
 * 科目
 *
 * 统计分析
 *
 */
class QuotationPrincipalService extends Service
{
	
	/**
	 *
	 * 科目信息
	 *
	 * @param $field 科目字段
	 * @param $status 科目状态
	 *
	 * @reutrn array;
	 */
	public function getPrincipalList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('QuotationPrincipal')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationPrincipal')->where($where);
			if($order){
				$handle->orderby($order);
			}
			
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$symbolIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['value'],
					'label'=>QuotationPrincipalModel::getStatusTitle($data['status'])
				);
				$listdata[$key]['period'] = explode(',',$data['period']);
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
	public function checkPrincipalTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('QuotationPrincipal')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 科目信息
	 *
	 * @param $principalId 科目ID
	 *
	 * @reutrn array;
	 */
	public function getPrincipalInfo($principalId,$field = '*'){
		
		$where = array(
			'identity'=>$principalId
		);
		
		$principalData = $this->model('QuotationPrincipal')->field($field)->where($where)->find();
		
		return $principalData;
	}
	
	/**
	 *
	 * 删除科目
	 *
	 * @param $principalId 科目ID
	 *
	 * @reutrn int;
	 */
	public function removePrincipalId($principalId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$principalId
		);
		
		$principalData = $this->model('QuotationPrincipal')->where($where)->find();
		if($principalData){
			
			$output = $this->model('QuotationPrincipal')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 科目修改
	 *
	 * @param $principalId 科目ID
	 * @param $principalNewData 科目数据
	 *
	 * @reutrn int;
	 */
	public function update($principalNewData,$principalId){
		$where = array(
			'identity'=>$principalId
		);
		
		$principalData = $this->model('QuotationPrincipal')->where($where)->find();
		if($principalData){
			
			$principalNewData['lastupdate'] = $this->getTime();
			$this->model('QuotationPrincipal')->data($principalNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新科目
	 *
	 * @param $principalNewData 科目数据
	 *
	 * @reutrn int;
	 */
	public function insert($principalNewData){
		
		$principalNewData['subscriber_identity'] =$this->session('uid');
		$principalNewData['dateline'] = $this->getTime();
			
		$principalNewData['lastupdate'] = $principalNewData['dateline'];
		$this->model('QuotationPrincipal')->data($principalNewData)->add();
	}
}