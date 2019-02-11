<?php
/**
 *
 * 业务
 *
 * 页面
 *
 */
class SecuritiesBusinessService extends Service
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
	public function getBusinessList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('SecuritiesBusiness')->where($where)->count();
		if($count){
			$handle = $this->model('SecuritiesBusiness')->where($where);
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
	public function checkBusinessTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('SecuritiesBusiness')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $businessId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getBusinessInfo($businessId,$field = '*'){
		
		$where = array(
			'identity'=>$businessId
		);
		
		$businessData = $this->model('SecuritiesBusiness')->field($field)->where($where)->find();
		
		return $businessData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $businessId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBusinessId($businessId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$businessId
		);
		
		$businessData = $this->model('SecuritiesBusiness')->where($where)->find();
		if($businessData){
			
			$output = $this->model('SecuritiesBusiness')->where($where)->delete();
			
			$this->service('PaginationItem')->removeBusinessIdAllItem($businessId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $businessId 模块ID
	 * @param $businessNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($businessNewData,$businessId){
		$where = array(
			'identity'=>$businessId
		);
		
		$businessData = $this->model('SecuritiesBusiness')->where($where)->find();
		if($businessData){
			
			$businessNewData['lastupdate'] = $this->getTime();
			$this->model('SecuritiesBusiness')->data($businessNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $businessNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($businessNewData){
		
		$businessNewData['subscriber_identity'] =$this->session('uid');
		$businessNewData['dateline'] = $this->getTime();
			
		$businessNewData['lastupdate'] = $businessNewData['dateline'];
		$this->model('SecuritiesBusiness')->data($businessNewData)->add();
	}
}