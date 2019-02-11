<?php
/**
 *
 * 职位
 *
 *
 */
class OrganizationPositionService extends Service
{
	
	/**
	 *
	 * 岗位信息
	 *
	 * @param $field 岗位字段
	 * @param $status 岗位状态
	 *
	 * @reutrn array;
	 */
	public function getPositionList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('OrganizationPosition')->where($where)->count();
		if($count){
			$positionHandle = $this->model('OrganizationPosition')->where($where)->orderby($order);
			if($perpage > 0){
				$positionHandle ->limit($start,$perpage,$count);
			}
			
			$listdata = $positionHandle->select();
			$quartersIds = array();
			foreach($listdata as $key=>$position){
				$quartersIds[] = $position['quarters_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>OrganizationPositionModel::getStatusTitle($data['status'])
				);		
			}
			$quartersData = $this->service('OrganizationQuarters')->getQuartersData($quartersIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['quarters'] = isset($quartersData[$data['quarters_identity']])?$quartersData[$data['quarters_identity']]:array();		
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 检测账户名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkPositionTitle($title,$quarters_identity){
		if($title){
				$where = array(
					'title'=>$title,
					'quarters_identity'=>$quarters_identity
				);
			return $this->model('OrganizationPosition')->where($where)->count();
		}
		return 0;
	}
	
	public function pushQuartersDefault($companyId,$departmentId,$quartersId){
		$array = array(
			'专员','工程师','组长','主管','经理','总监','副总'
		);
		
		$where = array();
		$where['company_identity'] = $companyId;
		$where['department_identity'] = $departmentId;
		$where['quarters_identity'] = $quartersId;
		$count = $this->model('OrganizationPosition')->where($where)->count();
		if($count){
			return 0;
		}
		
		
		$list = array();
		$curUid =$this->session('uid');
		$curTime = $this->getTime();
		$positionNewData['sn'] = $this->get_sn();
		
		foreach($array as $key=>$title){
			$list['title'][] = $title;
			$list['subscriber_identity'][] = $curUid;
			$list['dateline'][] = $curTime;
			$list['lastupdate'][] = $curTime;
			$list['sn'][] = $this->get_sn();
			$list['company_identity'][] = $companyId;
			$list['department_identity'][] = $departmentId;
			$list['quarters_identity'][] = $quartersId;
		}
		
		$this->model('OrganizationPosition')->data($list)->addMulti();
		$this->release();
	}
	
	/**
	 *
	 * 单位信息
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getPositionData($companyId){
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationPosition')->field('identity,title')->where($where)->select();
		
		return $companyData;
	}
	/**
	 *
	 * 岗位信息
	 *
	 * @param $positionId 岗位ID
	 *
	 * @reutrn array;
	 */
	public function getPositionInfo($positionId,$field = '*'){
		
		$where = array(
			'identity'=>$positionId
		);
		
		$positionData = $this->model('OrganizationPosition')->field($field)->where($where)->find();
		
		return $positionData;
	}
	
	/**
	 *
	 * 删除岗位
	 *
	 * @param $positionId 岗位ID
	 *
	 * @reutrn int;
	 */
	public function removePositionId($positionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$positionId
		);
		
		$positionData = $this->model('OrganizationPosition')->where($where)->find();
		if($positionData){
			
			$output = $this->model('OrganizationPosition')->where($where)->delete();
			$this->release($positionData['company_identity']);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 岗位修改
	 *
	 * @param $positionId 岗位ID
	 * @param $positionNewData 岗位数据
	 *
	 * @reutrn int;
	 */
	public function update($positionNewData,$positionId){
		$where = array(
			'identity'=>$positionId
		);
		
		$positionData = $this->model('OrganizationPosition')->where($where)->find();
		if($positionData){
			if(!$positionNewData['company_identity']){
				$positionNewData['company_identity'] = $positionData['company_identity'];
			}
			$positionNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationPosition')->data($positionNewData)->where($where)->save();
			$this->release($positionNewData['company_identity']);
		}
	}
	
	/**
	 *
	 * 新岗位
	 *
	 * @param $positionNewData 岗位数据
	 *
	 * @reutrn int;
	 */
	public function insert($positionNewData){
		
		$positionNewData['subscriber_identity'] =$this->session('uid');
		$positionNewData['dateline'] = $this->getTime();
		$positionNewData['sn'] = $this->get_sn();
			
		if(!$positionNewData['company_identity']){
			$positionNewData['company_identity'] = $this->session('company_identity');
		}
		$positionNewData['lastupdate'] = $positionNewData['dateline'];
		$positionId = $this->model('OrganizationPosition')->data($positionNewData)->add();
		
		$this->service('OrganizationTechnical')->pushTechnicalDefault($positionNewData['company_identity'],$positionNewData['department_identity'],$positionId);
		
		$this->release($positionNewData['company_identity']);
		return $positionId;
	}
	
	public function release($companyId){
		
		$list = array();
		$where = array();
		$where['status'] = OrganizationPositionModel::ORGANIZATION_POSITION_STATUS_ENABLE;
		$where['company_identity'] = $companyId;
		$positionList = $this->model('OrganizationPosition')->where($where)->select();
		if($positionList){
			$departmentIds = $quartersIds = array();
			foreach($positionList as $key=>$data){
				$departmentIds[] = $data['department_identity'];
				$quartersIds[] = $data['quarters_identity'];
			}
			$departmentList = $this->service('OrganizationDepartment')->getListByDepartmentIds($departmentIds);
			$quartersList = $this->service('OrganizationQuarters')->getListByQuartersIds($quartersIds);
			
			if($departmentList && $quartersList){
				foreach($departmentList as $key=>$depart){
					foreach($quartersList as $cnt=>$quarters){
						if($quarters['department_identity'] != $depart['identity']) continue;
						foreach($positionList as $col=>$position){
							if($position['quarters_identity'] != $quarters['identity']) continue;
							$quarters['s'][] = array(
								'id'=>$position['identity'],
								'title'=>$position['title']
							);
						}
						$depart['s'][] = array(
							'id'=>$quarters['identity'],
							'title'=>$quarters['title'],
							's'=>$quarters['s']
						);
					}
					$list[] = array(
						'id'=>$depart['identity'],
						'title'=>$depart['title'],
						's'=>$depart['s']
					);
				}
			}
			
		}
		
		$folder = __DATA__.'/json/organization';
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		$result = file_put_contents($folder.'/position_'.$companyId.'.json',json_encode($list,JSON_UNESCAPED_UNICODE));
	}
}