<?php
/**
 * 目录
 *
 * 资源库
 *
 */
class  ResourcesPlatformService extends Service {
	
	
	/**
	 *
	 * 目录信息
	 *
	 * @param $field 目录字段
	 * @param $status 目录状态
	 *
	 * @reutrn array;
	 */
	public function getPlatformList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ResourcesPlatform')->where($where)->count();
		if($count){
			$listdata = $this->model('ResourcesPlatform')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 目录信息
	 *
	 * @param $platformId 目录ID
	 *
	 * @reutrn array;
	 */
	public function getPlatformInfo($platformId,$field = '*'){
		
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('ResourcesPlatform')->field($field)->where($where)->find();
		
		return $platformData;
	}
	/**
	 *
	 * 检测目录名称
	 *
	 * @param $platformName 目录名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($platformName){
		if($platformName){
			$where = array(
				'title'=>$platformName,
				'subscriber_identity'=>$this->session('uid')
			);
			return $this->model('ResourcesPlatform')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除目录
	 *
	 * @param $platformId 目录ID
	 *
	 * @reutrn int;
	 */
	public function removeplatformId($platformId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('ResourcesPlatform')->where($where)->select();
		if($platformData){
			
			$output = $this->model('ResourcesPlatform')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 目录修改
	 *
	 * @param $platformId 目录ID
	 * @param $platformNewData 目录数据
	 *
	 * @reutrn int;
	 */
	public function update($platformNewData,$platformId){
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('ResourcesPlatform')->where($where)->find();
		if($platformData){
			
			$platformNewData['lastupdate'] = $this->getTime();
			$this->model('ResourcesPlatform')->data($platformNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新目录
	 *
	 * @param $title 目录名称
	 * @param $summary 目录介绍
	 * @param $permission 目录权限
	 * @param $status 目录状态
	 *
	 * @reutrn int;
	 */
	public function insert($title,$platform_identity,$remark,$status){
		$platformNewData = array(
			'title'=>$title,
			'platform_identity'=>$platform_identity,
			'remark'=>$remark,
			'business_identity'=>$this->session('business_identity'),
			'status'=>$status,
			'subscriber_identity'=>$this->session('uid'),
			'dateline'=>$this->getTime()
		);
			
		$platformNewData['lastupdate'] = $platformNewData['dateline'];
		$this->model('ResourcesPlatform')->data($platformNewData)->add();
	}
}