<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProductionPlatformService extends Service
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
	public function getPlatformList($where,$start,$perpage,$order = 'identity DESC'){
		
		$count = $this->model('ProductionPlatform')->where($where)->count();
		if($count){
			$handle = $this->model('ProductionPlatform')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$platformIds =$deviceIds = array();
			foreach($listdata as $key=>$data){
				$platformIds[] = $data['subject_identity'];
				$deviceIds[] = $data['device_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>ProductionPlatformModel::getStatusTitle($data['status'])
				);
			}
			
			$deviceData = $this->service('ProjectDevice')->getDeviceInfo($deviceIds);
			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($platformIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['device'] = isset($deviceData[$data['device_identity']])?$deviceData[$data['device_identity']]:array();
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
			}
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
	public function checkPlatformTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProductionPlatform')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $platformId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getPlatformInfo($platformId,$field = '*'){
		
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('ProductionPlatform')->field($field)->where($where)->find();
		
		return $platformData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $platformId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removePlatformId($platformId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('ProductionPlatform')->where($where)->find();
		if($platformData){
			
			$output = $this->model('ProductionPlatform')->where($where)->delete();
			
			$this->service('PaginationItem')->removePlatformIdAllItem($platformId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $platformId 模块ID
	 * @param $platformNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($platformNewData,$platformId){
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('ProductionPlatform')->where($where)->find();
		if($platformData){
			
			$platformNewData['lastupdate'] = $this->getTime();
			$this->model('ProductionPlatform')->data($platformNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $platformNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($platformNewData){
		
		$platformNewData['subscriber_identity'] =$this->session('uid');
		$platformNewData['dateline'] = $this->getTime();
		$platformNewData['sn'] = $this->get_sn();
			
		$platformNewData['lastupdate'] = $platformNewData['dateline'];
		$platformId = $this->model('ProductionPlatform')->data($platformNewData)->add();
		
		return $platformId;
	}
}