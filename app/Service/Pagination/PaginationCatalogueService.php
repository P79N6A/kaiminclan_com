<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class PaginationCatalogueService extends Service
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
	public function getCatalogueList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PaginationCatalogue')->where($where)->count();
		if($count){
            $handle = $this->model('PaginationCatalogue')->where($where);
            if($order){
                $handle ->order($order);
            }
            if($perpage){
                $handle ->limit($start,$perpage,$count);
            }
            $listdata = $handle->select();
            $platformIds = $domainIds = array();

            foreach ($listdata as $key=>$data){
                $platformIds[] = $data['platform_identity'];
                $domainIds[] = $data['domain_identity'];
                $listdata[$key]['status'] = array(
                    'value'=>$data['status'],
                    'label'=>PaginationCatalogueModel::getStatusTitle($data['status'])
                );
            }

            $platformData = $this->service('PaginationPlatform')->getPlatformInfo($platformIds);
            $domainData = $this->service('PaginationDomain')->getDomainInfo($platformIds);

            foreach ($listdata as $key=>$data){
                $listdata[$key]['platform'] = isset($platformData[$data['platform_identity']])?$platformData[$data['platform_identity']]:array();
                $listdata[$key]['domain'] = isset($domainData[$data['domain_identity']])?$domainData[$data['domain_identity']]:array();

            }
		}
		return array('total'=>$count,'list'=>$listdata);
	}
    public function getCatalogueByPlatformId($platformId){
        $where = array(
            'status'=>PaginationCatalogueModel::PAGINATION_CATALOGUE_STATUS_ENABLE,
            'platform_identity'=>$platformId
        );

        $list = $this->model('PaginationCatalogue')->field('identity,domain_identity,catalogue_identity,platform_identity,title,folder')->where($where)->select();

        return $list;
    }


    /**
	 *
	 * 模块信息
	 *
	 * @param $catalogueId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogueInfo($catalogueId){
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('PaginationCatalogue')->where($where)->select();
		
		return $catalogueData;
	}

    public function getCatalogueInfoByIds($catalogueIds){

        $where = array(
            'identity'=>$catalogueIds,
            'status'=>PaginationCatalogueModel::PAGINATION_CATALOGUE_STATUS_ENABLE
        );

        $domainData = $this->model('PaginationCatalogue')->where($where)->select();

        return $domainData;
    }
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $catalogueId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogueId($catalogueId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('PaginationCatalogue')->where($where)->find();
		if($catalogueData){
			
			$output = $this->model('PaginationCatalogue')->where($where)->delete();
            $this->release();
            $this->service('PaginationPage')->release();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $catalogueId 模块ID
	 * @param $catalogueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogueNewData,$catalogueId){
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('PaginationCatalogue')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$this->model('PaginationCatalogue')->data($catalogueNewData)->where($where)->save();
            $this->release();
            $this->service('PaginationPage')->release();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $catalogueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($catalogueNewData){

	    $catalogueNewData['sn'] = $this->get_sn();
		$catalogueNewData['subscriber_identity'] =$this->session('uid');
		$catalogueNewData['dateline'] = $this->getTime();
			
		$catalogueNewData['lastupdate'] = $catalogueNewData['dateline'];
		$catalogueId = $this->model('PaginationCatalogue')->data($catalogueNewData)->add();
		if($catalogueId){
            $this->release();
            $this->service('PaginationPage')->release();
        }
	}

	public function release(){
        $list = array();

        $where = array(
            'status'=>PaginationCatalogueModel::PAGINATION_CATALOGUE_STATUS_ENABLE
        );

        $listdata = $this->model('PaginationCatalogue')->where($where)->select();
        if($listdata){
            $domainIds = $platformIds = array();
            foreach ($listdata as $key=>$data){
                $domainIds[] = $data['domain_identity'];
                $platformIds[] = $data['platform_identity'];
            }
            $domainData = $this->service('PaginationDomain')->getDomainInfoByIds($domainIds);
            $platformData = $this->service('PaginationPlatform')->getPlatformInfoByIds($domainIds);
            if($domainData && $platformData){
                foreach ($domainData as $key=>$domain){
                    foreach ($platformData as $cnt=>$platform){
                        if($platform['domain_identity'] != $domain['identity']) continue;
                        foreach ($listdata as $col=>$catalogue){
                            if($catalogue['platform_identity'] != $platform['identity']) continue;
                            $platform['s'][] = $catalogue;
                        }
                        $domain['s'][] = $platform;
                    }
                    $list[] = $domain;
                }
            }
        }

        $folder = __DATA__.'/json/pagination';
        if(!is_dir($folder)){
            mkdir($folder,0777,1);
        }

        $result = file_put_contents($folder.'/catalogue.json',json_encode($list,JSON_UNESCAPED_UNICODE));
    }
}