<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProductionFrontendService extends Service
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
	public function getFrontendList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProductionFrontend')->where($where)->count();
		if($count){
			$handle = $this->model('ProductionFrontend')->where($where);
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
	public function checkFrontendTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProductionFrontend')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $frontendId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getFrontendInfo($frontendId,$field = '*'){
		
		$where = array(
			'identity'=>$frontendId
		);
		
		$frontendData = $this->model('ProductionFrontend')->field($field)->where($where)->find();
		
		return $frontendData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $frontendId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeFrontendId($frontendId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$frontendId
		);
		
		$frontendData = $this->model('ProductionFrontend')->where($where)->find();
		if($frontendData){
			
			$output = $this->model('ProductionFrontend')->where($where)->delete();
			
			$this->service('PaginationItem')->removeFrontendIdAllItem($frontendId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $frontendId 模块ID
	 * @param $frontendNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($frontendNewData,$frontendId){
		$where = array(
			'identity'=>$frontendId
		);
		
		$frontendData = $this->model('ProductionFrontend')->where($where)->find();
		if($frontendData){
			
			$frontendNewData['lastupdate'] = $this->getTime();
			$this->model('ProductionFrontend')->data($frontendNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $frontendNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($frontendNewData){
		
		$frontendNewData['subscriber_identity'] =$this->session('uid');
		$frontendNewData['dateline'] = $this->getTime();
		$frontendNewData['sn'] = $this->get_sn();
			
		$frontendNewData['lastupdate'] = $frontendNewData['dateline'];
		$frontendId = $this->model('ProductionFrontend')->data($frontendNewData)->add();
		
		return $frontendId;
	}
}