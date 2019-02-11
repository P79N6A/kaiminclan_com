<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class GeographyNationalityService extends Service
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
	public function getNationalityList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('GeographyNationality')->where($where)->count();
		if($count){
			$handle = $this->model('GeographyNationality')->where($where);
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
	public function checkNationalityTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('GeographyNationality')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $nationalityId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getNationalityInfo($nationalityId,$field = '*'){
		
		$where = array(
			'identity'=>$nationalityId
		);
		
		$nationalityData = $this->model('GeographyNationality')->field($field)->where($where)->find();
		
		return $nationalityData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $nationalityId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeNationalityId($nationalityId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$nationalityId
		);
		
		$nationalityData = $this->model('GeographyNationality')->where($where)->find();
		if($nationalityData){
			
			$output = $this->model('GeographyNationality')->where($where)->delete();
			
			$this->service('PaginationItem')->removeNationalityIdAllItem($nationalityId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $nationalityId 模块ID
	 * @param $nationalityNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($nationalityNewData,$nationalityId){
		$where = array(
			'identity'=>$nationalityId
		);
		
		$nationalityData = $this->model('GeographyNationality')->where($where)->find();
		if($nationalityData){
			
			$nationalityNewData['lastupdate'] = $this->getTime();
			$this->model('GeographyNationality')->data($nationalityNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $nationalityNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($nationalityNewData){
		
		$nationalityNewData['subscriber_identity'] =$this->session('uid');
		$nationalityNewData['dateline'] = $this->getTime();
		$nationalityNewData['sn'] = $this->get_sn();
			
		$nationalityNewData['lastupdate'] = $nationalityNewData['dateline'];
		$this->model('GeographyNationality')->data($nationalityNewData)->add();
	}
}