<?php
/**
 *
 * 分类
 *
 * 融资
 *
 */
class PermanentFashionService extends Service
{
	public function adjustCredit($fashionId,$quantity){
		
		if($fashionId < 1){
			return -1;
		}
		
		if(strcmp($quantity,0) === 0){
			return -2;
		}
		
		$where = array(
			'identity'=>$fashionId
		);
		
		$fashionData = $this->model('PermanentFashion')->where($where)->find();
		if(!$fashionData){
			return -3;
		}
		
		if($quantity < 0 ){
			//借
			$fashionNewData = array(
				'credit_num'=>$fashionData['credit_num']-substr($quantity,1),
				'lastupdate'=>$this->getTime()
			);
		}else{
			//还
			$fashionNewData = array(
				'credit_num'=>$fashionData['credit_num']+$quantity,
				'lastupdate'=>$this->getTime()
			);
		}
		
		$this->model('PermanentFashion')->data($fashionNewData)->where($where)->save();
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getFashionList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PermanentFashion')->where($where)->count();
		if($count){
			$handle = $this->model('PermanentFashion')->where($where);
			$start = intval($start);
			$perpage = intval($perpage);
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
	public function checkFashionTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('PermanentFashion')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $channelId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getFashionInfo($channelId,$field = '*'){
		$fashionData = array();
		
		$channelId = $this->getInt($channelId);
		if(!$channelId){
			return $fashionData;
		}
		
		$where = array(
			'identity'=>$channelId
		);
		
		$fashionList = $this->model('PermanentFashion')->field($field)->where($where)->select();
		if($fashionList){
			$fashionData = $fashionList;
			if(!is_array($channelId)){
				$fashionData = current($fashionList);
			}
		}
		return $fashionData;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $channelId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeFashionId($channelId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$channelId
		);
		
		$fashionData = $this->model('PermanentFashion')->where($where)->find();
		if($fashionData){
			
			$output = $this->model('PermanentFashion')->where($where)->delete();
			
		}
		
		return $output;
	}
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $channelId 分类ID
	 * @param $fashionNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($fashionNewData,$channelId){
		$where = array(
			'identity'=>$channelId
		);
		
		$fashionData = $this->model('PermanentFashion')->where($where)->find();
		if($fashionData){
			
			$fashionNewData['lastupdate'] = $this->getTime();
			$this->model('PermanentFashion')->data($fashionNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $fashionNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function insert($fashionNewData){
		
		$fashionNewData['subscriber_identity'] =$this->session('uid');
		$fashionNewData['dateline'] = $this->getTime();
			
		$fashionNewData['lastupdate'] = $fashionNewData['dateline'];
		$fashionNewData['sn'] = $this->get_sn();
		
		$fashionId = $this->model('PermanentFashion')->data($fashionNewData)->add();
		return $fashionId;
	}
}