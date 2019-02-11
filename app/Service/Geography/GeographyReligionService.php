<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class GeographyReligionService extends Service
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
	public function getReligionList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('GeographyReligion')->where($where)->count();
		if($count){
			$handle = $this->model('GeographyReligion')->where($where);
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
	public function checkReligionTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('GeographyReligion')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $religionId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getReligionInfo($religionId,$field = '*'){
		
		$where = array(
			'identity'=>$religionId
		);
		
		$religionData = $this->model('GeographyReligion')->field($field)->where($where)->find();
		
		return $religionData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $religionId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeReligionId($religionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$religionId
		);
		
		$religionData = $this->model('GeographyReligion')->where($where)->find();
		if($religionData){
			
			$output = $this->model('GeographyReligion')->where($where)->delete();
			
			$this->service('PaginationItem')->removeReligionIdAllItem($religionId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $religionId 模块ID
	 * @param $religionNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($religionNewData,$religionId){
		$where = array(
			'identity'=>$religionId
		);
		
		$religionData = $this->model('GeographyReligion')->where($where)->find();
		if($religionData){
			
			$religionNewData['lastupdate'] = $this->getTime();
			$this->model('GeographyReligion')->data($religionNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $religionNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($religionNewData){
		
		$religionNewData['subscriber_identity'] =$this->session('uid');
		$religionNewData['dateline'] = $this->getTime();
		$religionNewData['sn'] = $this->get_sn();
			
		$religionNewData['lastupdate'] = $religionNewData['dateline'];
		$this->model('GeographyReligion')->data($religionNewData)->add();
	}
}