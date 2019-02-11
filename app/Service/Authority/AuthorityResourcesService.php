<?php
class AuthorityResourcesService extends Service {
	
	
	public function getResourcesList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('AuthorityResources')->where($where)->count();
		if($count){
			$selectHandle = $this->model('AuthorityResources')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getResourcesListByUser($roleId,$subscriberId){
		$resourcesId = array();
		//获取账户资源
		$where = array(
			'idtype'=>AuthorityResourcesModel::AUTHORITY_RESOURCES_AUTHORITY_TYPE_USER,
			'id'=>$subscriberId,
			'status'=>AuthorityResourcesModel::AUTHORITY_RESOURCES_STATUS_ENABLE
		);
		
		$list = $this->model('AuthorityResources')->where($where)->select();
		if($list){
			foreach($list as $key=>$data){
				$block  = AuthorityResourcesModel::getResoucesTypeCode($data['resources_idtype']);
				if(!$block){
					continue;
				}
				$resourcesId[$block][] = $data['resources_id'];
			}
		}
		
		
		//获取角色资源
		$where = array(
			'idtype'=>AuthorityResourcesModel::AUTHORITY_RESOURCES_AUTHORITY_TYPE_ROLE,
			'id'=>$roleId,
			'status'=>AuthorityResourcesModel::AUTHORITY_RESOURCES_STATUS_ENABLE
		);		
		$list = $this->model('AuthorityResources')->where($where)->select();
		if($list){
			foreach($list as $key=>$data){
				$block  = AuthorityResourcesModel::getResoucesTypeCode($data['resources_idtype']);
				if(!$block){
					continue;
				}
				$resourcesId[$block][] = $data['resources_id'];
			}
		}
		
		if($resourcesId){			
			foreach($resourcesId as $block=>$ids){
				$resourcesId[$block] = array_unique($ids);
			}
		}
		
		return $resourcesId;
	}

	public function getList($roleId,$subscriberId){
        $list = array();

        $where = array(
            'idtype'=>2,
            'id'=>$roleId
        );

        $resources = array();
        $listdata = $this->model('AuthorityResources')->where($where)->select();
        if(!$listdata){
            return $list;
        }
        foreach ($listdata as $key=>$data){
            $resources[$data['resources_idtype']][] = $data['resources_id'];
        }
        if(!$resources){
            return $list;
        }

        foreach ($resources as $type=>$ids){
            switch ($type){
                case 1:
                    //平台
                    $platform  = $this->service('PaginationPlatform')->getPlatformInfoByIds($ids);
                    break;
                case 2:
                    //业务
                    $catalogue  = $this->service('PaginationCatalogue')->getCatalogueInfoByIds($ids);
                    break;
                case 3:
                    //页面
                    $page  = $this->service('PaginationPage')->getPageInfoByIds($ids);
                    if($page){
                        $catIds = array();
                        foreach ($page as $cnt=>$data){
                            $catIds[] = $data['catalogue_identity'];
                        }
                        $catalogue  = $this->service('PaginationCatalogue')->getCatalogueInfoByIds($catIds);
                        if($catalogue){
                            foreach ($page as $cnt=>$data){
                                $catId = $data['catalogue_identity'];
								if($data['weight']) continue;
                                if(isset($catalogue[$catId])){
                                    if(!isset($list[$catId])) {
                                        $list[$catId] = $catalogue[$data['catalogue_identity']];
                                    }
									$data['url'] = (str_replace('-','/',$data['url']));
                                    $list[$catId]['child'][] = $data;
                                }
                            }
                        }

                    }
                    break;
                case 4:
                    //应用
                    break;
                case 5:
                    //模块
                    break;
                case 6:
                    //接口
                    break;
            }
        }
        return $list;
    }
}