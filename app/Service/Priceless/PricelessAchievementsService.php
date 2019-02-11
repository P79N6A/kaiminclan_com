<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class PricelessAchievementsService extends Service
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
	public function getAchievementsList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PricelessAchievements')->where($where)->count();
		if($count){
			$handle = $this->model('PricelessAchievements')->where($where);
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
	public function checkAchievementsTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('PricelessAchievements')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $achievementsId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getAchievementsInfo($achievementsId,$field = '*'){
		$achievementsData = array();
		
		if(!is_array($achievementsId)){
			$achievementsId = array($achievementsId);
		}
		
		$achievementsId = array_filter(array_map('intval',$achievementsId));
		
		if(!empty($achievementsId)){
			
			$where = array(
				'identity'=>$achievementsId
			);
			
			$achievementsData = $this->model('PricelessAchievements')->field($field)->where($where)->select();
		}
		return $achievementsData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $achievementsId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeAchievementsId($achievementsId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$achievementsId
		);
		
		$achievementsData = $this->model('PricelessAchievements')->where($where)->find();
		if($achievementsData){
			
			$output = $this->model('PricelessAchievements')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $achievementsId 模块ID
	 * @param $achievementsNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($achievementsNewData,$achievementsId){
		$where = array(
			'identity'=>$achievementsId
		);
		
		$achievementsData = $this->model('PricelessAchievements')->where($where)->find();
		if($achievementsData){
			
			$achievementsNewData['lastupdate'] = $this->getTime();
			$this->model('PricelessAchievements')->data($achievementsNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $achievementsNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($achievementsNewData){
		
		$achievementsNewData['subscriber_identity'] =$this->session('uid');
		$achievementsNewData['dateline'] = $this->getTime();
		$achievementsNewData['sn'] = $this->get_sn();
			
		$achievementsNewData['lastupdate'] = $achievementsNewData['dateline'];
		$this->model('PricelessAchievements')->data($achievementsNewData)->add();
	}
}