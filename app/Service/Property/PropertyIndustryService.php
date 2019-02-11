<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  PropertyIndustryService extends Service {
	
	
	
	public function adjustCapitalTotal($scaleId,$quantity = 1){
		
		$scaleId = $this->getInt($scaleId);
		if(!empty($scaleId)){
			$where = array(
				'identity'=>$scaleId
			);
			if(strpos($quantity,'-') !== false){
				$this->model('PropertyIndustry')->where($where)->setDec('capital_num',substr($quantity,1));
			}else{
				$this->model('PropertyIndustry')->where($where)->setInc('capital_num',$quantity);
			}
		}
	}
	/**
	 *
	 * 收藏列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getIndustryList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PropertyIndustry')->where($where)->count();
		if($count){
			$industryHandle = $this->model('PropertyIndustry')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$industryHandle = $industryHandle->limit($start,$perpage,$count);
			}
			$listdata = $industryHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $industryIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getIndustryInfo($industryIds){
		$industryData = array();
		
		$where = array(
			'identity'=>$industryIds
		);
		
		$industryList = $this->model('PropertyIndustry')->where($where)->select();
		if($industryList){
			
			if(is_array($industryIds)){
				$industryData = $industryList;
			}else{
				$industryData = current($industryList);
			}
			
			
		}
		
		
		return $industryData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $industryId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeIndustryId($industryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$industryId
		);
		
		$industryData = $this->model('PropertyIndustry')->where($where)->count();
		if($industryData){
			
			$output = $this->model('PropertyIndustry')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkIndustryMobile($mobile){
		$industryId = array();		
		$where = array(
			'mobile'=>$mobile,
		);
		
		
		return $this->model('PropertyIndustry')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $industryId 收藏ID
	 * @param $industryNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($industryNewData,$industryId){
		$where = array(
			'identity'=>$industryId
		);
		
		$industryData = $this->model('PropertyIndustry')->where($where)->find();
		if($industryData){
			
			$industryNewData['lastupdate'] = $this->getTime();
			$this->model('PropertyIndustry')->data($industryNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($industryData){
		$dateline = $this->getTime();
		$industryData['subscriber_identity'] = $this->session('uid');
		$industryData['dateline'] = $dateline;
		$industryData['lastupdate'] = $dateline;
			
		$industryId = $this->model('PropertyIndustry')->data($industryData)->add();
		if($industryId){
		}
		return $industryId;
	}
}