<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class GeographyMountainService extends Service
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
	public function getMountainList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('GeographyMountain')->where($where)->count();
		if($count){
			$handle = $this->model('GeographyMountain')->where($where);
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
	public function checkMountainTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('GeographyMountain')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $mountainId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getMountainInfo($mountainId,$field = '*'){
		
		$where = array(
			'identity'=>$mountainId
		);
		
		$mountainData = $this->model('GeographyMountain')->field($field)->where($where)->find();
		
		return $mountainData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $mountainId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeMountainId($mountainId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$mountainId
		);
		
		$mountainData = $this->model('GeographyMountain')->where($where)->find();
		if($mountainData){
			
			$output = $this->model('GeographyMountain')->where($where)->delete();
			
			$this->service('PaginationItem')->removeMountainIdAllItem($mountainId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $mountainId 模块ID
	 * @param $mountainNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($mountainNewData,$mountainId){
		$where = array(
			'identity'=>$mountainId
		);
		
		$mountainData = $this->model('GeographyMountain')->where($where)->find();
		if($mountainData){
			
			$mountainNewData['lastupdate'] = $this->getTime();
			$this->model('GeographyMountain')->data($mountainNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $mountainNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($mountainNewData){
		
		$mountainNewData['subscriber_identity'] =$this->session('uid');
		$mountainNewData['dateline'] = $this->getTime();
		$mountainNewData['sn'] = $this->get_sn();
			
		$mountainNewData['lastupdate'] = $mountainNewData['dateline'];
		$this->model('GeographyMountain')->data($mountainNewData)->add();
	}
}