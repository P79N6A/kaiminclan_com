<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class GeographyRiversService extends Service
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
	public function getRiversList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('GeographyRivers')->where($where)->count();
		if($count){
			$handle = $this->model('GeographyRivers')->where($where);
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
	public function checkRiversTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('GeographyRivers')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $riversId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getRiversInfo($riversId,$field = '*'){
		
		$where = array(
			'identity'=>$riversId
		);
		
		$riversData = $this->model('GeographyRivers')->field($field)->where($where)->find();
		
		return $riversData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $riversId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeRiversId($riversId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$riversId
		);
		
		$riversData = $this->model('GeographyRivers')->where($where)->find();
		if($riversData){
			
			$output = $this->model('GeographyRivers')->where($where)->delete();
			
			$this->service('PaginationItem')->removeRiversIdAllItem($riversId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $riversId 模块ID
	 * @param $riversNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($riversNewData,$riversId){
		$where = array(
			'identity'=>$riversId
		);
		
		$riversData = $this->model('GeographyRivers')->where($where)->find();
		if($riversData){
			
			$riversNewData['lastupdate'] = $this->getTime();
			$this->model('GeographyRivers')->data($riversNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $riversNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($riversNewData){
		
		$riversNewData['subscriber_identity'] =$this->session('uid');
		$riversNewData['dateline'] = $this->getTime();
		$riversNewData['sn'] = $this->get_sn();
			
		$riversNewData['lastupdate'] = $riversNewData['dateline'];
		return $this->model('GeographyRivers')->data($riversNewData)->add();
	}
}