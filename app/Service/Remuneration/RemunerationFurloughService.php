<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RemunerationFurloughService extends Service
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
	public function getFurloughList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RemunerationFurlough')->where($where)->count();
		if($count){
			$handle = $this->model('RemunerationFurlough')->where($where);
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
	public function checkFurloughTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RemunerationFurlough')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $furloughId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getFurloughInfo($furloughId,$field = '*'){
		$furloughData = array();
		
		if(!is_array($furloughId)){
			$furloughId = array($furloughId);
		}
		
		$furloughId = array_filter(array_map('intval',$furloughId));
		
		if(!empty($furloughId)){
			
			$where = array(
				'identity'=>$furloughId
			);
			
			$furloughData = $this->model('RemunerationFurlough')->field($field)->where($where)->select();
		}
		return $furloughData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $furloughId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeFurloughId($furloughId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$furloughId
		);
		
		$furloughData = $this->model('RemunerationFurlough')->where($where)->find();
		if($furloughData){
			
			$output = $this->model('RemunerationFurlough')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $furloughId 模块ID
	 * @param $furloughNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($furloughNewData,$furloughId){
		$where = array(
			'identity'=>$furloughId
		);
		
		$furloughData = $this->model('RemunerationFurlough')->where($where)->find();
		if($furloughData){
			
			$furloughNewData['lastupdate'] = $this->getTime();
			$this->model('RemunerationFurlough')->data($furloughNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $furloughNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($furloughNewData){
		
		$furloughNewData['subscriber_identity'] =$this->session('uid');
		$furloughNewData['dateline'] = $this->getTime();
			
		$furloughNewData['lastupdate'] = $furloughNewData['dateline'];
		$this->model('RemunerationFurlough')->data($furloughNewData)->add();
	}
}