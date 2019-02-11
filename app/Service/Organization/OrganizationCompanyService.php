
<?php
/**
 *
 * 单位
 *
 * 页面
 *
 */
class OrganizationCompanyService extends Service
{
	public function adjustDepartmentQuantity($companyId,$quantity = 1){
		
		$companyId = $this->getInt($companyId);
		$quantity = $this->getInt($quantity);
		if(!$companyId || !$quantity){
			return 0;
		}
		
		$where = array(
			'identity'=>$companyId
		);
		
		if(strpos($quantity,'-') !== false){
			$this->model('OrganizationCompany')->where($where)->setDec('department_num',substr($quantity,1));
		}else{
			$this->model('OrganizationCompany')->where($where)->setInc('department_num',$quantity);
		}
		
		
	}
	/**
	 *
	 * 单位信息
	 *
	 * @param $field 单位字段
	 * @param $status 单位状态
	 *
	 * @reutrn array;
	 */
	public function getCompanyList($where,$start,$perpage,$order = 'identity desc'){
		
		$count = $this->model('OrganizationCompany')->where($where)->count();
		if($count){
			$handle = $this->model('OrganizationCompany')->where($where);
			if($order){
				$handle->orderby($order);
			}
			if($perpage){
				$handle->limit($start,$perpage,$count);
			}
			
			$listdata = $handle->select();
			
			$districtIds = $motionIds = array();
			foreach($listdata as $key=>$data){
				$districtIds[] = $data['continent_district_identity'];
				$districtIds[] = $data['region_district_identity'];
				$districtIds[] = $data['country_district_identity'];
				$motionIds[] = $data['motion_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>OrganizationCompanyModel::getStatusTitle($data['status'])
				);
			}
			
			$disrtrictData = $this->service('GeographyDistrict')->getDistrictInfo($districtIds);
			$motionData = $this->service('OrganizationMotion')->getMotionInfo($motionIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['continent'] = isset($disrtrictData[$data['continent_district_identity']])?$disrtrictData[$data['continent_district_identity']]:array();
				$listdata[$key]['region'] = isset($disrtrictData[$data['region_district_identity']])?$disrtrictData[$data['region_district_identity']]:array();
				$listdata[$key]['country'] = isset($disrtrictData[$data['country_district_identity']])?$disrtrictData[$data['country_district_identity']]:array();
				$listdata[$key]['motion'] = isset($motionData[$data['motion_identity']])?$motionData[$data['motion_identity']]:array();
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
	public function checkCompanyTitle($title,$cid){
		if($title){
				$where = array(
					'title'=>$title,
					'company_identity'=>$cid
				);
			return $this->model('OrganizationCompany')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 单位信息
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getCompanyInfo($companyId,$field = '*'){
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationCompany')->field($field)->where($where)->find();
		
		return $companyData;
	}
	
	/**
	 *
	 * 单位信息
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getCompanyData($companyId){
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationCompany')->field('identity,title')->where($where)->select();
		
		return $companyData;
	}
	
	/**
	 *
	 * 删除单位
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn int;
	 */
	public function removeCompanyId($companyId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationCompany')->where($where)->find();
		if($companyData){
			
			$output = $this->model('OrganizationCompany')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 单位修改
	 *
	 * @param $companyId 单位ID
	 * @param $companyNewData 单位数据
	 *
	 * @reutrn int;
	 */
	public function update($companyNewData,$companyId){
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationCompany')->where($where)->find();
		if($companyData){
			
			$companyNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationCompany')->data($companyNewData)->where($where)->save();
			if($companyData['title'] != $companyNewData['title']){
				$this->service('MechanismAccount')->changeCompany($companyId,$companyNewData['title']);
			}
			if($companyData['department_num'] < 1){
				$this->service('OrganizationDepartment')->newDepartment($companyId);
			}
		}
	}
	
	/**
	 *
	 * 新单位
	 *
	 * @param $companyNewData 单位数据
	 *
	 * @reutrn int;
	 */
	public function insert($companyNewData){
		
		$companyNewData['subscriber_identity'] =$this->session('uid');
		$companyNewData['dateline'] = $this->getTime();
		$companyNewData['sn'] = $this->get_sn();
			
		$companyNewData['lastupdate'] = $companyNewData['dateline'];
		$companyId = $this->model('OrganizationCompany')->data($companyNewData)->add();
		if($companyId){
			$this->service('MechanismAccount')->newCompany($companyId,$companyNewData['title']);
			$this->service('OrganizationDepartment')->newDepartment($companyId);
		}
		return $companyId;
	}
}