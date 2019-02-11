<?php
/**
 *
 * 等级
 *
 * 分销
 *
 */
class  DistributionGradeService extends Service {
	
	
	/**
	 *
	 * 活动列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getGradeList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('DistributionGrade')->where($where)->count();
		if($count){
			$gradeHandle = $this->model('DistributionGrade')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$gradeHandle = $gradeHandle->limit($start,$perpage,$count);
			}
			$listdata = $gradeHandle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>DistributionGradeModel::getStatusTitle($data['status'])
				);
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 活动信息
	 *
	 * @param $gradeIds 活动ID
	 *
	 * @reutrn int;
	 */
	public function getGradeInfo($gradeIds){
		$gradeData = array();
		
		$where = array(
			'identity'=>$gradeIds
		);
		
		$gradeList = $this->model('DistributionGrade')->where($where)->select();
		if($gradeList){
			
			if(is_array($gradeIds)){
				$gradeData = $gradeList;
			}else{
				$gradeData = current($gradeList);
			}
			
			
		}
		
		
		return $gradeData;
	}
	
	
		
	/**
	 *
	 * 删除活动
	 *
	 * @param $gradeId 活动ID
	 *
	 * @reutrn int;
	 */
	public function removeGradeId($gradeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$gradeId
		);
		
		$gradeData = $this->model('DistributionGrade')->where($where)->count();
		if($gradeData){
			
			$output = $this->model('DistributionGrade')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测活动
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkGradeTitle($title){
		$gradeId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('DistributionGrade')->where($where)->count();
	}
	
	/**
	 *
	 * 活动修改
	 *
	 * @param $gradeId 活动ID
	 * @param $gradeNewData 活动数据
	 *
	 * @reutrn int;
	 */
	public function update($gradeNewData,$gradeId){
		$where = array(
			'identity'=>$gradeId
		);
		
		$gradeData = $this->model('DistributionGrade')->where($where)->find();
		if($gradeData){
			
			
			$gradeNewData['lastupdate'] = $this->getTime();
			$this->model('DistributionGrade')->data($gradeNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新活动
	 *
	 * @param $id 活动信息
	 * @param $idtype 活动信息
	 *
	 * @reutrn int;
	 */
	public function insert($gradeData){
		$dateline = $this->getTime();
		$gradeData['subscriber_identity'] = $this->session('uid');
		$gradeData['dateline'] = $dateline;
		$gradeData['lastupdate'] = $dateline;
		$gradeData['sn'] = $this->get_sn();
			
		$gradeId = $this->model('DistributionGrade')->data($gradeData)->add();
		if($gradeId){
		}
		return $gradeId;
	}
}