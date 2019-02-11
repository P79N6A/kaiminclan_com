<?php
/**
 *
 * 数据
 *
 * 页面
 *
 */
class OrganizationDepartmentService extends Service
{
	
	/**
	 *
	 * 条目信息
	 *
	 * @param $field 条目字段
	 * @param $status 条目状态
	 *
	 * @reutrn array;
	 */
	public function getDepartmentList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('OrganizationDepartment')->where($where)->count();
		if($count){
			$selectHandel = $this->model('OrganizationDepartment')->where($where)->orderby($order);
			if($perpage){
				$selectHandel->limit($start,$perpage,$count);
			}
			$listdata = $selectHandel->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 部门信息
	 *
	 * @param $companyId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getDepartmentData($companyId){
		
		$where = array(
			'identity'=>$companyId
		);
		
		$companyData = $this->model('OrganizationDepartment')->field('identity,title')->where($where)->select();
		
		return $companyData;
	}
	
	public function getListByDepartmentIds($departmentId){
		$list = array();
		$departmentId = $this->getInt($departmentId);
		if($departmentId){
			$where = array();
			$where['status'] = OrganizationDepartmentModel::ORGANIZATION_DEPARTMENT_STATUS_ENABLE;
			$where['identity'] = $departmentId;
			$list = $this->model('OrganizationDepartment')->where($where)->select();
		}
		return $list;
	}
	
	/**
	 *
	 * 条目信息
	 *
	 * @param $itemId 条目ID
	 *
	 * @reutrn array;
	 */
	public function getDepartmentInfo($itemId,$field = '*'){
		
		$where = array(
			'identity'=>$itemId
		);
		
		$itemData = $this->model('OrganizationDepartment')->field($field)->where($where)->find();
		
		return $itemData;
	}
	/**
	 *
	 * 检测账户名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($title,$companyId,$departmentId){
		if($title){
				$where = array(
					'title'=>$title,
					'company_identity'=>$companyId,
					'department_identity'=>$departmentId
				);
			return $this->model('OrganizationDepartment')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除条目
	 *
	 * @param $itemId 条目ID
	 *
	 * @reutrn int;
	 */
	public function removeDepartmentId($itemId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$itemId
		);
		
		$itemData = $this->model('OrganizationDepartment')->where($where)->find();
		if($itemData){
			
			$output = $this->model('OrganizationDepartment')->where($where)->delete();
			$this->service('OrganizationPosition')->release();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 删除模块下所有条目
	 *
	 * @param $blockId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBlockIdAllDepartment($blockId){
		
		$output = 0;
		
		$where = array(
			'block_identity'=>$blockId
		);
		
		$itemData = $this->model('OrganizationDepartment')->where($where)->find();
		if($itemData){
			
			$output = $this->model('OrganizationDepartment')->where($where)->delete();
			$this->service('OrganizationPosition')->release($itemData['company_identity']);
			$this->service('OrganizationQuarters')->release($itemData['company_identity']);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 条目修改
	 *
	 * @param $itemId 条目ID
	 * @param $itemNewData 条目数据
	 *
	 * @reutrn int;
	 */
	public function update($itemNewData,$itemId){
		$where = array(
			'identity'=>$itemId
		);
		
		$itemData = $this->model('OrganizationDepartment')->where($where)->find();
		if($itemData){
			
			$itemNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationDepartment')->data($itemNewData)->where($where)->save();
			$this->service('OrganizationPosition')->release($itemNewData['company_identity']);
			$this->service('OrganizationQuarters')->release($itemNewData['company_identity']);
		}
	}
	
	public function newDepartment($companyId){
		$this->insert(array('company_identity'=>$companyId,'title'=>'办公室'));
	}
	
	/**
	 *
	 * 新条目
	 *
	 * @param $itemNewData 条目数据
	 *
	 * @reutrn int;
	 */
	public function insert($itemNewData){
		
		$itemNewData['subscriber_identity'] =$this->session('uid');
		$itemNewData['dateline'] = $this->getTime();
		$itemNewData['sn'] = $this->get_sn();
			
		$itemNewData['lastupdate'] = $itemNewData['dateline'];
		$departmentId = $this->model('OrganizationDepartment')->data($itemNewData)->add();
		if($departmentId){
			$this->service('OrganizationCompany')->adjustDepartmentQuantity($itemNewData['company_identity']);
			$this->service('OrganizationPosition')->release($itemNewData['company_identity']);
			$this->service('OrganizationQuarters')->release($itemNewData['company_identity']);
		}
	}
}