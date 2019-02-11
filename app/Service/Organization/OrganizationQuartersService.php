<?php
/**
 *
 * 岗位
 *
 *
 */
class OrganizationQuartersService extends Service
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
	public function getQuartersList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('OrganizationQuarters')->where($where)->count();
		if($count){
			$selectHandel = $this->model('OrganizationQuarters')->where($where)->orderby($order);
			if($perpage > 0){
				$selectHandel ->limit($start,$perpage,$count);
			}
			
			$listdata = $selectHandel->select();
			$companyIds = $departmentIds = $quartersIds = $positionIds = array();
			foreach($listdata as $key=>$data){
				$companyIds[] = $data['company_identity'];		
				$departmentIds[] = $data['department_identity'];	
				$quartersIds[] = $data['quarters_identity'];	
				$positionIds[] = $data['position_identity'];	
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>OrganizationQuartersModel::getStatusTitle($data['status'])
				);		
			}
			
			$companyData = $this->service('OrganizationCompany')->getCompanyData($companyIds);
			$departmentData = $this->service('OrganizationDepartment')->getDepartmentData($departmentIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['company'] = isset($companyData[$data['company_identity']])?$companyData[$data['company_identity']]:array();	
				$listdata[$key]['department'] = isset($departmentData[$data['department_identity']])?$departmentData[$data['department_identity']]:array();
			}
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getListByQuartersIds($quartersId){
		$list = array();
		$quartersId = $this->getInt($quartersId);
		if($quartersId){
			$where = array();
			$where['status'] = OrganizationQuartersModel::ORGANIZATION_QUARTERS_STATUS_ENABLE;
			$where['identity'] = $quartersId;
			$list = $this->model('OrganizationQuarters')->where($where)->select();
		}
		return $list;
	}
	
	public function getRoleIdByQuartersId($quartersId){
		$roleId = 0;
		
		$quartersId = intval($quartersId);
		
		$where = array(
			'identity'=>$quartersId
		);
		$quartersData = $this->model('OrganizationQuarters')->field('bind_role_identity')->where($where)->find();
		if($quartersData){
			$roleId = $quartersData['bind_role_identity'];
		}
		
		return $roleId;
	}
	/**
	 *
	 * 部门信息
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getQuartersData($companyId){
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationQuarters')->field('identity,title')->where($where)->select();
		
		return $companyData;
	}
	
	/**
	 *
	 * 岗位信息
	 *
	 * @param $quartersId 岗位ID
	 *
	 * @reutrn array;
	 */
	public function getQuartersInfo($quartersId,$field = '*'){
		
		$where = array(
			'identity'=>$quartersId
		);
		
		$quartersData = $this->model('OrganizationQuarters')->field($field)->where($where)->find();
		
		return $quartersData;
	}
	
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkQuartersTitle($title,$department_identity){
		if($title){
				$where = array(
					'title'=>$title,
					'department_identity'=>$department_identity
				);
			return $this->model('OrganizationQuarters')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除岗位
	 *
	 * @param $quartersId 岗位ID
	 *
	 * @reutrn int;
	 */
	public function removeQuartersId($quartersId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$quartersId
		);
		
		$quartersData = $this->model('OrganizationQuarters')->where($where)->find();
		if($quartersData){
			
			$output = $this->model('OrganizationQuarters')->where($where)->delete();
			$this->service('OrganizationPosition')->release($quartersData['company_identity']);
			$this->release($quartersData['company_identity']);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 岗位修改
	 *
	 * @param $quartersId 岗位ID
	 * @param $quartersNewData 岗位数据
	 *
	 * @reutrn int;
	 */
	public function update($quartersNewData,$quartersId){
		$where = array(
			'identity'=>$quartersId
		);
		
		$quartersData = $this->model('OrganizationQuarters')->where($where)->find();
		if($quartersData){
			if(!$quartersNewData['company_identity']){
				$quartersNewData['company_identity'] = $quartersData['company_identity'];
			}
			
			$quartersNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationQuarters')->data($quartersNewData)->where($where)->save();
			$this->service('OrganizationPosition')->pushQuartersDefault($quartersNewData['company_identity'],$quartersNewData['department_identity'],$quartersId);
			$this->service('OrganizationPosition')->release($quartersNewData['company_identity']);
			$this->release($quartersNewData['company_identity']);
		}
	}
	
	/**
	 *
	 * 新岗位
	 *
	 * @param $quartersNewData 岗位数据
	 *
	 * @reutrn int;
	 */
	public function insert($quartersNewData){
		
		$quartersNewData['subscriber_identity'] =$this->session('uid');
		$quartersNewData['dateline'] = $this->getTime();
			
		$quartersNewData['lastupdate'] = $quartersNewData['dateline'];
		$quartersNewData['sn'] = $this->get_sn();
			
		if(!$quartersNewData['company_identity']){
			$quartersNewData['company_identity'] = $this->session('company_identity');
		}
		$this->model('OrganizationQuarters')->start();
		
		$quartersId = $this->model('OrganizationQuarters')->data($quartersNewData)->add();
		if($quartersId){
			$lastInsertRoleId = $this->service('AuthorityRole')->newQuartersRole($quartersId,$quartersNewData['title']);
			if($lastInsertRoleId < 0){
				$this->model('OrganizationQuarters')->rollback();
			}
			
			$where = array();
			$where['identity'] = $quartersId;
			
			$bindQuartersData = array(
				'bind_role_identity'=>$lastInsertRoleId
			);
			$this->model('OrganizationQuarters')->data($bindQuartersData)->where($where)->save();
		}
		$this->service('OrganizationPosition')->pushQuartersDefault($quartersNewData['company_identity'],$quartersNewData['department_identity'],$quartersId);
		$this->model('OrganizationQuarters')->commit();
		$this->service('OrganizationPosition')->release($quartersNewData['company_identity']);
		
		$this->release($quartersNewData['company_identity']);
		return $quartersId;
	}
	
	public function release($companyId){
		
		$list = array();
		$where = array();
		$where['status'] = OrganizationPositionModel::ORGANIZATION_POSITION_STATUS_ENABLE;
		$where['company_identity'] = $companyId;
		$quartersList = $this->model('OrganizationQuarters')->where($where)->select();
		if($quartersList){
			$departmentIds = $quartersIds = array();
			foreach($quartersList as $key=>$data){
				$departmentIds[] = $data['department_identity'];
			}
			$departmentList = $this->service('OrganizationDepartment')->getListByDepartmentIds($departmentIds);
			
			if($departmentList){
				foreach($departmentList as $key=>$depart){
					foreach($quartersList as $cnt=>$quarters){
						if($quarters['department_identity'] != $depart['identity']) continue;
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
		$result = file_put_contents($folder.'/quarters_'.$companyId.'.json',json_encode($list,JSON_UNESCAPED_UNICODE));
	}
}