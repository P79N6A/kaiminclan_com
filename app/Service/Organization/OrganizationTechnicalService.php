<?php
/**
 *
 * 模板
 *
 *
 */
class OrganizationTechnicalService extends Service
{
	
	/**
	 *
	 * 模板信息
	 *
	 * @param $field 模板字段
	 * @param $status 模板状态
	 *
	 * @reutrn array;
	 */
	public function getTechnicalList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('OrganizationTechnical')->where($where)->count();
		if($count){
			$selectHandel = $this->model('OrganizationTechnical')->where($where)->orderby($order);
			if($perpage > 0){
				$selectHandel ->limit($start,$perpage,$count);
			}
			
			$listdata = $selectHandel->select();
			$companyIds = $departmentIds = $quartersIds = $positionIds = $quartersIds = array();
			foreach($listdata as $key=>$data){
				$companyIds[] = $data['company_identity'];		
				$departmentIds[] = $data['department_identity'];	
				$positionIds[] = $data['position_identity'];	
				$quartersIds[] = $data['quarters_identity'];	
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>OrganizationTechnicalModel::getStatusTitle($data['status'])
				);		
			}
			$companyData = $this->service('OrganizationCompany')->getCompanyData($companyIds);
			$departmentData = $this->service('OrganizationDepartment')->getDepartmentData($departmentIds);
			$positionData = $this->service('OrganizationPosition')->getPositionData($positionIds);
			$quartersData = $this->service('OrganizationQuarters')->getQuartersData($quartersIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['company'] = isset($companyData[$data['company_identity']])?$companyData[$data['company_identity']]:array();	
				$listdata[$key]['department'] = isset($departmentData[$data['department_identity']])?$departmentData[$data['department_identity']]:array();
				$listdata[$key]['position'] = isset($positionData[$data['position_identity']])?$positionData[$data['position_identity']]:array();
				$listdata[$key]['quarters'] = isset($quartersData[$data['quarters_identity']])?$quartersData[$data['quarters_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 模板信息
	 *
	 * @param $templateId 模板ID
	 *
	 * @reutrn array;
	 */
	public function getTechnicalInfo($templateId,$field = '*'){
		
		$where = array(
			'identity'=>$templateId
		);
		
		$templateData = $this->model('OrganizationTechnical')->field($field)->where($where)->find();
		
		return $templateData;
	}
	
	public function pushTechnicalDefault($companyId,$departmentId,$positoinId){
		$array = array(
			'学徒','实习','助理','初级专员','高级工程师','资深工程师','技术专家','高级专家','资深专家','研究员','高级研究员','科学家','高级科学家','首席科学家'
		);
		
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
			$list['position_identity'][] = $positoinId;
		}
		
		$this->model('OrganizationTechnical')->data($list)->addMulti();
	}
	/**
	 *
	 * 检测账户名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($title,$position_identity){
		if($title){
				$where = array(
					'title'=>$title,
					'position_identity'=>$position_identity
				);
			return $this->model('OrganizationTechnical')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除模板
	 *
	 * @param $templateId 模板ID
	 *
	 * @reutrn int;
	 */
	public function removeTechnicalId($templateId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$templateId
		);
		
		$templateData = $this->model('OrganizationTechnical')->where($where)->find();
		if($templateData){
			
			$output = $this->model('OrganizationTechnical')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模板修改
	 *
	 * @param $templateId 模板ID
	 * @param $templateNewData 模板数据
	 *
	 * @reutrn int;
	 */
	public function update($templateNewData,$templateId){
		$where = array(
			'identity'=>$templateId
		);
		
		$templateData = $this->model('OrganizationTechnical')->where($where)->find();
		if($templateData){
			
			$templateNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationTechnical')->data($templateNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模板
	 *
	 * @param $templateNewData 模板数据
	 *
	 * @reutrn int;
	 */
	public function insert($templateNewData){
		
		$templateNewData['subscriber_identity'] =$this->session('uid');
		$templateNewData['dateline'] = $this->getTime();
			
		$templateNewData['lastupdate'] = $templateNewData['dateline'];
		$templateNewData['sn'] = $this->get_sn();
		$this->model('OrganizationTechnical')->data($templateNewData)->add();
	}
}