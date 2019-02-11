<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  PropertyScaleService extends Service {
	
	
	
	public function adjustCapitalTotal($scaleId,$quantity = 1){
		
		$scaleId = $this->getInt($scaleId);
		if(!empty($scaleId)){
			$where = array(
				'identity'=>$scaleId
			);
			if(strpos($quantity,'-') !== false){
				$this->model('PropertyScale')->where($where)->setDec('capital_num',substr($quantity,1));
			}else{
				$this->model('PropertyScale')->where($where)->setInc('capital_num',$quantity);
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
	public function getScaleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PropertyScale')->where($where)->count();
		if($count){
			$scaleHandle = $this->model('PropertyScale')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$scaleHandle = $scaleHandle->limit($start,$perpage,$count);
			}
			$listdata = $scaleHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $scaleIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getScaleInfo($scaleIds){
		$scaleData = array();
		
		$where = array(
			'identity'=>$scaleIds
		);
		
		$scaleList = $this->model('PropertyScale')->where($where)->select();
		if($scaleList){
			
			if(is_array($scaleIds)){
				$scaleData = $scaleList;
			}else{
				$scaleData = current($scaleList);
			}
			
			
		}
		
		
		return $scaleData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $scaleId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeScaleId($scaleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$scaleId
		);
		
		$scaleData = $this->model('PropertyScale')->where($where)->count();
		if($scaleData){
			
			$output = $this->model('PropertyScale')->where($where)->delete();
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
	public function checkScaleMobile($mobile){
		$scaleId = array();		
		$where = array(
			'mobile'=>$mobile,
		);
		
		
		return $this->model('PropertyScale')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $scaleId 收藏ID
	 * @param $scaleNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($scaleNewData,$scaleId){
		$where = array(
			'identity'=>$scaleId
		);
		
		$scaleData = $this->model('PropertyScale')->where($where)->find();
		if($scaleData){
			
			if($scaleNewData['mobile'] != $scaleData['mobile']){
				$isValid = $this->service('AuthoritySubscriber')->changeScaleMobileByClientId($scaleId,$scaleNewData['mobile']);
				if(!$isValid){
					return -1;
				}
			}
			
			$scaleNewData['lastupdate'] = $this->getTime();
			$this->model('PropertyScale')->data($scaleNewData)->where($where)->save();
			
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
	public function insert($scaleData){
		$dateline = $this->getTime();
		$scaleData['subscriber_identity'] = $this->session('uid');
		$scaleData['dateline'] = $dateline;
		$scaleData['lastupdate'] = $dateline;
			
		$scaleId = $this->model('PropertyScale')->data($scaleData)->add();
		if($scaleId){
			$this->service('AuthoritySubscriber')->newScaleUser($scaleId,$scaleData['mobile'],$scaleData['fullname']);
		}
		return $scaleId;
	}
}