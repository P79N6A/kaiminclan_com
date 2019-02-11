<?php
/**
 *
 * 机会
 *
 * 统计分析
 *
 */
class QuotationOpportunityService extends Service
{
	
	/**
	 *
	 * 机会信息
	 *
	 * @param $field 机会字段
	 * @param $status 机会状态
	 *
	 * @reutrn array;
	 */
	public function getOpportunityList($where,$start,$perpage,$order = 'identity DESC'){
		
		$count = $this->model('QuotationOpportunity')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationOpportunity')->where($where);
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
	
	/**
	 *
	 * 机会信息
	 *
	 * @param $opportunityId 机会ID
	 *
	 * @reutrn array;
	 */
	public function getOpportunityInfo($opportunityId,$field = '*'){
		
		$where = array(
			'identity'=>$opportunityId
		);
		
		$opportunityData = $this->model('QuotationOpportunity')->field($field)->where($where)->find();
		
		return $opportunityData;
	}
	
	/**
	 *
	 * 删除机会
	 *
	 * @param $opportunityId 机会ID
	 *
	 * @reutrn int;
	 */
	public function removeOpportunityId($opportunityId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$opportunityId
		);
		
		$opportunityData = $this->model('QuotationOpportunity')->where($where)->find();
		if($opportunityData){
			
			$output = $this->model('QuotationOpportunity')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 机会修改
	 *
	 * @param $opportunityId 机会ID
	 * @param $opportunityNewData 机会数据
	 *
	 * @reutrn int;
	 */
	public function update($opportunityNewData,$opportunityId){
		$where = array(
			'identity'=>$opportunityId
		);
		
		$opportunityData = $this->model('QuotationOpportunity')->where($where)->find();
		if($opportunityData){
			
			$opportunityNewData['lastupdate'] = $this->getTime();
			$this->model('QuotationOpportunity')->data($opportunityNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新机会
	 *
	 * @param $opportunityNewData 机会数据
	 *
	 * @reutrn int;
	 */
	public function insert($opportunityNewData){
		
		$opportunityNewData['dateline'] = $this->getTime();
			
		$opportunityNewData['lastupdate'] = $opportunityNewData['dateline'];
		$this->model('QuotationOpportunity')->data($opportunityNewData)->add();
	}
}