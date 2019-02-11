<?php
/**
 *
 * 角色
 *
 * 权限
 *
 */
class  AuthorityRoleService extends Service {
	
	
	/**
	 *
	 * 角色账户统计
	 *
	 * @param $roleId 角色ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustSubscriberNum($roleId,$quantity = 1){
		
		$where = array(
			'identity' =>$roleId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('AuthorityRole')->where($where)->setInc('subscriber_num',1);
					break;
				case -1:
					$this->model('AuthorityRole')->where($where)->setDec('subscriber_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 提取角色类型
	 *
	 * @param $roleId 角色ID
	 *
	 * @reutrn array;
	 */
	public function getRoleType($roleId){
        $cacheKey = 'authortiy_role_type';
		$roleTypeData = $this->cache($cacheKey);
		if(!isset($roleTypeData[$roleId]) || $roleTypeData[$roleId] < 1){
            $roleType = 0;
            $where = array();
            $_roleId = $roleId;
		    while(true){
                $where['identity'] = $_roleId;
                $roleData = $this->model('AuthorityRole')->field('title,dateline,role_identity,identity')->where($where)->find();
                if(!$roleData) {
                    break;
                }
                if($roleData['role_identity'] < 1){
                    $roleType = $roleData['identity'];
                    break;
                }else{
                    $_roleId = $roleData['role_identity'];
                }
            }
            $roleTypeData[$roleId] = $roleType;
            $this->cache($cacheKey,$roleTypeData);
        }
		
		return $roleTypeData[$roleId];
	}
	
	/**
	 *
	 * 角色信息
	 *
	 * @param $field 角色字段
	 * @param $status 角色状态
	 *
	 * @reutrn array;
	 */
	public function getRoleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('AuthorityRole')->where($where)->count();
		
		if($count){
			$subscriberHandle = $this->model('AuthorityRole')->where($where);
			if($start &&  $perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			
			foreach($listdata as $key=>$data){
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>AuthorityRoleModel::getStatusTitle($data['status'])
				);
			}
		}
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 角色信息
	 *
	 * @param $roleId 角色ID
	 *
	 * @reutrn array;
	 */
	public function getRoleInfo($roleId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$roleId
		);
		
		$roleData = array();
		if(is_array($roleId)){
			$roleList = $this->model('AuthorityRole')->field($field)->where($where)->select();
			if($roleList){
				foreach($roleList as $key=>$role){
					$roleData[$role['identity']] = $role;
				}
			}
		}else{
			$roleData = $this->model('AuthorityRole')->field($field)->where($where)->find();
		}
		return $roleData;
	}

	public function getRoleInfoByRoleId(){
	    $where = array(
	        'status'=>AuthorityRoleModel::AUTHORITY_ROLE_STATUS_ENABLE
        );
	    return $this->model('AuthorityRole')->where($where)->select();
    }
	/**
	 *
	 * 检测角色名称
	 *
	 * @param $roleName 角色名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($roleName){
		if($roleName){
			$where = array(
				'title'=>$roleName
			);
			return $this->model('AuthorityRole')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除角色
	 *
	 * @param $roleId 角色ID
	 *
	 * @reutrn int;
	 */
	public function removeRoleId($roleId){
		
		$output = 0;
		
		if(count($roleId) < 1){
			return $output;
		}
		
		$disabledRoleIds = AuthorityRoleModel::getRoleTypeList();
		foreach($roleId as $key=>$rid){
			if(in_array($rid,$disabledRoleIds)){
				unset($roleId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$roleId
		);
		
		$roleData = $this->model('AuthorityRole')->where($where)->select();
		if($roleData){
			
			$output = $this->model('AuthorityRole')->where($where)->delete();
			$this->pushJson();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 新岗位
	 *
	 * @param $quartersId 角色ID
	 * @param $quartersTitle 角色数据
	 *
	 * @reutrn int;
	 */
	
	public function newQuartersRole($quartersId,$quartersTitle){
		
		$role_identity = 2;
		$where = array();
		$where['title'] = $quartersTitle;
		$where['role_identity'] = $role_identity;
		$count = $this->model('AuthorityRole')->where($where)->count();
		if($count){
			return -1;
		}
		$roleData= array(
			'id'=>$quartersId,
			'idtype'=>2,
			'title'=>$quartersTitle,
			'role_identity'=>$role_identity
		);
		return $this->insert($roleData);
	}
	
	/**
	 *
	 * 角色修改
	 *
	 * @param $roleId 角色ID
	 * @param $roleNewData 角色数据
	 *
	 * @reutrn int;
	 */
	public function update($roleNewData,$roleId){
		$where = array(
			'identity'=>$roleId
		);
		
		$roleData = $this->model('AuthorityRole')->where($where)->find();
		if($roleData){
			
			$roleNewData['lastupdate'] = $this->getTime();
			$this->model('AuthorityRole')->data($roleNewData)->where($where)->save();
			$this->pushJson();
		}
	}
	
	/**
	 *
	 * 新角色
	 *
	 * @param $roleNewData 角色信息
	 *
	 * @reutrn int;
	 */
	public function insert($roleNewData){
		if(!$roleNewData){
			return -1;
		}
		$roleNewData['business_identity'] =$this->session('business_identity');
		$roleNewData['subscriber_identity'] =$this->session('uid');
		$roleNewData['dateline'] = $this->getTime();
		
		$roleId = $this->model('AuthorityRole')->data($roleNewData)->add();
		if($roleId){
			$this->pushJson();
		}
		return $roleId;
	}
	
	private function pushJson(){
		set_time_limit(0);
		$treeList = array();
		$where = array('status'=>0);
		$listdata = $this->model('AuthorityRole')->field('identity as id,role_identity as pid,code,title')->where($where)->select();
		
		if($listdata){
			foreach($listdata as $key=>$data){
				if($data['pid']) continue;
				foreach($listdata as $cnt=>$sub_data){
					if($sub_data['pid'] == $data['id']){
						foreach($listdata as $sub_cnt=>$sub_sub_data){
							if($sub_sub_data['pid'] == $sub_data['id']){
								foreach($listdata as $sub_sub_cnt=>$sub_sub_sub_data){
									if($sub_sub_sub_data['pid'] == $sub_sub_data['id']){
										$sub_sub_data['s'][] = $sub_sub_sub_data;
									}
								}
								$sub_data['s'][] = $sub_sub_data;
							}
						}
                        $data['s'][] = $sub_data;
					}
				}
                $treeList[] = $data;
			}
		}

		
		$folder = __DATA__.'/json/authority';
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		file_put_contents($folder.'/role.json',json_encode($treeList,JSON_UNESCAPED_UNICODE));
	}
}