<?php
/**
 *
 * 页面
 *
 *
 */
class PaginationPageService extends Service
{
	
	/**
	 *
	 * 页面信息
	 *
	 * @param $field 页面字段
	 * @param $status 页面状态
	 *
	 * @reutrn array;
	 */
	public function getPageList($where,$start,$perpage,$order = 'identity desc'){
		
		$count = $this->model('PaginationPage')->where($where)->count();
		if($count){
            $handle = $this->model('PaginationPage')->where($where);
            if($order){
                $handle ->order($order);
            }
            if($perpage){
                $handle ->limit($start,$perpage,$count);
            }
			$listdata = $handle->select();
            $roleIds = $platformIds = $domainIds = $catalogueIds = array();

            foreach ($listdata as $key=>$data){
                $platformIds[] = $data['platform_identity'];
                $domainIds[] = $data['domain_identity'];
                $catalogueIds[] = $data['catalogue_identity'];
                $roleIds[] = $data['role_identity'];
                $listdata[$key]['status'] = array(
                    'value'=>$data['status'],
                    'label'=>PaginationPageModel::getStatusTitle($data['status'])
                );
                $listdata[$key]['setting'] = json_decode($data['setting'],true);
            }

            $platformData = $this->service('PaginationPlatform')->getPlatformInfo($platformIds);
            $domainData = $this->service('PaginationDomain')->getDomainInfo($domainIds);
            $catalogueData = $this->service('PaginationCatalogue')->getCatalogueInfo($catalogueIds);
            $roleData = $this->service('AuthorityRole')->getRoleInfo($roleIds);

            foreach ($listdata as $key=>$data){
                $listdata[$key]['platform'] = isset($platformData[$data['platform_identity']])?$platformData[$data['platform_identity']]:array();
                $listdata[$key]['domain'] = isset($domainData[$data['domain_identity']])?$domainData[$data['domain_identity']]:array();
                $listdata[$key]['role'] = isset($roleData[$data['role_identity']])?$roleData[$data['role_identity']]:array();
                $listdata[$key]['catalogue'] = isset($catalogueData[$data['catalogue_identity']])?$catalogueData[$data['catalogue_identity']]:array();
            }
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function checkPageTitle($title,$catalogueId){
		$where = array(
			'title'=>$title,
			'catalogue_identity'=>$catalogueId
		);
		return $this->model('PaginationPage')->where($where)->count();
	}
	
	/**
	 *
	 * 页面浏览数
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustPageView($pageId,$quantity = 1){
		
		$where = array(
			'identity' =>$pageId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('PaginationPage')->where($where)->setInc('view_num',1);
					break;
				case -1:
					$this->model('PaginationPage')->where($where)->setDec('view_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 短链接
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function getShortUrl(){
	}
	/**
	 *
	 * 页面访问数
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustVisitView($pageId,$quantity = 1){
		
		$where = array(
			'identity' =>$pageId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('PaginationPage')->where($where)->setInc('visitor_num',1);
					break;
				case -1:
					$this->model('PaginationPage')->where($where)->setDec('visitor_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 页面信息
	 *
	 * @param $pageId 页面ID
	 *
	 * @reutrn array;
	 */
	public function getPageInfo($pageId,$field = '*'){
		
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('PaginationPage')->field($field)->where($where)->find();
		
		return $pageData;
	}

	public function getPageInfoByIds($pageId){
        $where = array(
            'identity'=>$pageId,
            'status'=>PaginationPageModel::PAGINATION_PAGE_STATUS_ENABLE
        );

        $pageData = $this->model('PaginationPage')->where($where)->select();

        return $pageData;
    }
	
	/**
	 *
	 * 删除页面
	 *
	 * @param $pageId 页面ID
	 *
	 * @reutrn int;
	 */
	public function removePageId($pageId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('PaginationPage')->where($where)->find();
		if($pageData){
			
			$output = $this->model('PaginationPage')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 页面修改
	 *
	 * @param $pageId 页面ID
	 * @param $pageNewData 页面数据
	 *
	 * @reutrn int;
	 */
	public function update($pageNewData,$pageId){
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('PaginationPage')->where($where)->find();
		if($pageData){
			
			$pageNewData['lastupdate'] = $this->getTime();
			$this->model('PaginationPage')->data($pageNewData)->where($where)->save();
			$this->release();
		}
	}
	
	/**
	 *
	 * 新页面
	 *
	 * @param $pageNewData 页面数据
	 *
	 * @reutrn int;
	 */
	public function insert($pageNewData){

        $pageNewData['sn'] = $this->get_sn();
		$pageNewData['subscriber_identity'] =$this->session('uid');
		$pageNewData['dateline'] = $this->getTime();
			
		$pageNewData['lastupdate'] = $pageNewData['dateline'];
		$pageId = $this->model('PaginationPage')->data($pageNewData)->add();
        if($pageId){
            $this->release();
        }
        return $pageId;
	}

	public function getPageByCatalogueId($catalogueIds){
        $where = array(
            'status'=>PaginationPageModel::PAGINATION_PAGE_STATUS_ENABLE,
            'catalogue_identity'=>$catalogueIds
        );

        $list = $this->model('PaginationPage')->where($where)->select();
        if($list){
            $roleIds = array();
            foreach ($list as $key=>$page){
                $roleIds[] = $page['role_identity'];
            }
            $roleData = $this->service('AuthorityRole')->getRoleInfoByRoleId($roleIds);
            foreach ($list as $key=>$data){
                if(!isset($roleData[$data['role_identity']])){
                    unset($list[$key]);
                    continue;
                }
                $list[$key]['role'] = $roleData[$data['role_identity']];
            }
        }

        return $list;
    }

	public function pushRoute(){
		$isUpdate = false;
		$domainList = $this->service('PaginationDomain')->getAllDomain();
		if($domainList){
			$domainIds = array();
			foreach($domainList as $key=>$data){
				$domainIds[] = $data['identity'];
			}
			$platformList = $this->service('PaginationPlatform')->getPlatformByDomainId($domainIds);
			if($platformList){
				$platformIds = array();
				foreach($platformList as $key=>$data){
					$platformIds[] = $data['identity'];
				}
				$catalogueList = $this->service('PaginationCatalogue')->getCatalogueByPlatformId($platformIds);
				if($catalogueList){
					$catalogueIds = array();
					foreach($catalogueList as $key=>$data){
						$catalogueIds[] = $data['identity'];
					}
					$pageList = $this->getPageByCatalogueId($catalogueIds);
					if($pageList){
						$routeFile = 'route';
						foreach($domainList as $cnt=>$domain){
							if(empty($domain['code'])) continue;
							if($domain['code'] != __DOMAIN__){
								$routeFile = $domain['code'];
							}
							$isUpdate = false;
							$route = '<?xml version="1.0" encoding="utf-8"?>'."\r\n";;
							$route .= '<config>'."\r\n";
							foreach($pageList as $key=>$data){
								if(!isset($platformList[$data['platform_identity']]) || empty($platformList[$data['platform_identity']]['code'])){
									continue;
								}
								if(!isset($catalogueList[$data['catalogue_identity']]) || empty($catalogueList[$data['catalogue_identity']]['folder'])){
									continue;
								}
								$isUpdate = true;
								$route .= '    <route>'."\r\n";
								$route .= '		<url>'.$data['url'].'</url>'."\r\n";
								$route .= '		<domain>'.$platformList[$data['platform_identity']]['code'].'</domain>'."\r\n";
								$route .= '		<permission>'.$data['role']['code'].'</permission>'."\r\n";
								$route .= '		<folder>'.$catalogueList[$data['catalogue_identity']]['folder'].'</folder>'."\r\n";
								$route .= '		<template>'.$data['primaltplname'].'</template>'."\r\n";
								$setting = json_decode($data['setting'],true);
								if($setting){
									foreach($setting as $cnt=>$param){
										$route .= '		<param>'."\r\n";
										foreach($param as $field=>$val){
											$route .= '			<'.$field.'>'.$val.'</'.$field.'>'."\r\n";
										}
										$route .= '		</param>'."\r\n";
									}
								}
								$route .= '		<seo>'."\r\n";
								$route .= '			<seotitle>'.$data['seotitle'].'</seotitle>'."\r\n";
								$route .= '			<seokeyword>'.$data['seokeyword'].'</seokeyword>'."\r\n";
								$route .= '			<seodescription>'.$data['seodescription'].'</seodescription>'."\r\n";
								$route .= '		</seo>'."\r\n";
								$route .= '    </route>'."\r\n";
							}
							$route .= '</config>'."\r\n";
							if($isUpdate){
								$result = file_put_contents(__ROOT__.'/config/'.$routeFile.'.xml',$route);
							}
						}
					}
				}
			}
			
		}
    }

    public function release(){
        $list = array();

        $folder = __DATA__.'/json/pagination';
        if(!is_dir($folder)){
            mkdir($folder,0777,1);
        }

        if($list){
            $result = file_put_contents($folder.'/page.json',json_encode($list,JSON_UNESCAPED_UNICODE));

        }
		$this->pushRoute();
    }
}