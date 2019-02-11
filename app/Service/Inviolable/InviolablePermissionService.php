<?php
/**
 *
 * 权限
 *
 * 权益
 *
 */
class InviolablePermissionService extends Service
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
	public function getPermissionList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('InviolablePermission')->where($where)->count();
		if($count){
			$handle = $this->model('InviolablePermission')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$catalogIds = array();
			foreach($listdata as $key=>$data){
				$catalogIds[] = $data['catalogue_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MaterialProductModel::getStatusTitle($data['status'])
				);
			}
			
			$catalogData = $this->service('DebentureCatalogue')->getCatalogueInfo($catalogIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['catalogue'] = isset($catalogData[$data['catalogue_identity']])?$catalogData[$data['catalogue_identity']]:array();
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
	public function checkPermissionTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('InviolablePermission')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $permissionId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getPermissionInfo($permissionId){
		
		$where = array(
			'identity'=>$permissionId
		);
		
		$permissionData = $this->model('InviolablePermission')->where($where)->select();
		
		return $permissionData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $permissionId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removePermissionId($permissionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$permissionId
		);
		
		$permissionData = $this->model('InviolablePermission')->where($where)->find();
		if($permissionData){
			
			$output = $this->model('InviolablePermission')->where($where)->delete();
		}
		
		return $output;
	}
	
	public function getAuthorizeByEmployeeId($employeeId){
		
		$symbolIds = $industry = $columnIds = array();
		
		$where = array();
		$where['employee_identity'] = $employeeId;
		$listdata = $this->model('InviolablePermission')->where($where)->select();
		if($listdata){
			foreach($listdata as $key=>$data){
				$id[] = $data['id'];
				switch($data['idtype']){
					case InviolablePermissionModel::INVIOLABLE_PERMISSION_IDTYPE_INDUSTRY:
						$industry[] = $data['id'];
						break;
					case InviolablePermissionModel::INVIOLABLE_PERMISSION_IDTYPE_COLUMN:
						$columnIds[] = $data['id'];
						break;
					case InviolablePermissionModel::INVIOLABLE_PERMISSION_IDTYPE_SYMBOL:
						$symbolIds[] = $data['id'];
						break;
				}
			}
		}
		
		return array('symbol'=>$symbolIds,'industry'=>$industry,'column'=>$columnIds);
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $permissionId 模块ID
	 * @param $permissionNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($permissionNewData,$permissionId){
		$where = array(
			'identity'=>$permissionId
		);
		
		$permissionData = $this->model('InviolablePermission')->where($where)->find();
		if($permissionData){
			
			$permissionNewData['lastupdate'] = $this->getTime();
			$this->model('InviolablePermission')->data($permissionNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $permissionNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($permissionNewData){
		
		$permissionNewData['subscriber_identity'] =$this->session('uid');
		$permissionNewData['dateline'] = $this->getTime();
			
		$permissionNewData['lastupdate'] = $permissionNewData['dateline'];
		$this->model('InviolablePermission')->data($permissionNewData)->add();
	}
}