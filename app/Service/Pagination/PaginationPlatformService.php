<?php
/**
 *
 * 模板
 *
 *
 */
class PaginationPlatformService extends Service
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
	public function getPlatformList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PaginationPlatform')->where($where)->count();
		if($count){
            $handle = $this->model('PaginationPlatform')->where($where);
            if($order){
                $handle ->order($order);
            }
            if($perpage){
                $handle ->limit($start,$perpage,$count);
            }
            $listdata = $handle->select();
            $platformIds = $domainIds = $catalogueIds = array();

            foreach ($listdata as $key=>$data){
                $domainIds[] = $data['domain_identity'];
                $listdata[$key]['status'] = array(
                   'value'=>$data['status'],
                   'label'=>PaginationPlatformModel::getStatusTitle($data['status'])
                );
            }

            $domainData = $this->service('PaginationDomain')->getDomainInfo($domainIds);

            foreach ($listdata as $key=>$data){
                $listdata[$key]['domain'] = isset($domainData[$data['domain_identity']])?$domainData[$data['domain_identity']]:array();

            }
		}
		return array('total'=>$count,'list'=>$listdata);
	}
    public function getPlatformByDomainId($domainId){
        $where = array(
            'status'=>PaginationDomainModel::PAGINATION_BLOCK_STATUS_ENABLE,
            'domain_identity'=>$domainId
        );

        $list = $this->model('PaginationPlatform')->field('identity,domain_identity,title,code')->where($where)->select();

        return $list;
    }
	
	/**
	 *
	 * 模板信息
	 *
	 * @param $platformId 模板ID
	 *
	 * @reutrn array;
	 */
	public function getPlatformInfo($platformId){
		
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('PaginationPlatform')->where($where)->select();
		
		return $platformData;
	}


    public function getPlatformInfoByIds($domainId){

        $where = array(
            'identity'=>$domainId,
            'status'=>PaginationPlatformModel::PAGINATION_BLOCK_STATUS_ENABLE
        );

        $domainData = $this->model('PaginationPlatform')->where($where)->select();

        return $domainData;
    }
	
	/**
	 *
	 * 删除模板
	 *
	 * @param $platformId 模板ID
	 *
	 * @reutrn int;
	 */
	public function removePlatformId($platformId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('PaginationPlatform')->where($where)->find();
		if($platformData){
			
			$output = $this->model('PaginationPlatform')->where($where)->delete();
            $this->release();
            $this->service('PaginationCatalogue')->release();
            $this->service('PaginationPage')->release();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模板修改
	 *
	 * @param $platformId 模板ID
	 * @param $platformNewData 模板数据
	 *
	 * @reutrn int;
	 */
	public function update($platformNewData,$platformId){
		$where = array(
			'identity'=>$platformId
		);
		
		$platformData = $this->model('PaginationPlatform')->where($where)->find();
		if($platformData){
			
			$platformNewData['lastupdate'] = $this->getTime();
			$this->model('PaginationPlatform')->data($platformNewData)->where($where)->save();
            $this->release();
            $this->service('PaginationCatalogue')->release();
            $this->service('PaginationPage')->release();
		}
	}
	
	/**
	 *
	 * 新模板
	 *
	 * @param $platformNewData 模板数据
	 *
	 * @reutrn int;
	 */
	public function insert($platformNewData){

        $platformNewData['sn'] = $this->get_sn();
		$platformNewData['subscriber_identity'] =$this->session('uid');
		$platformNewData['dateline'] = $this->getTime();
			
		$platformNewData['lastupdate'] = $platformNewData['dateline'];
		$paltformId = $this->model('PaginationPlatform')->data($platformNewData)->add();
        if($paltformId){
            $this->release();
            $this->service('PaginationCatalogue')->release();
            $this->service('PaginationPage')->release();
        }
        return $paltformId;
	}

    public function release(){
        $list = array();

        $where = array(
            'status'=>PaginationPlatformModel::PAGINATION_BLOCK_STATUS_ENABLE
        );

        $listdata = $this->model('PaginationPlatform')->where($where)->select();
        if($listdata){
            $domainIds = array();
            foreach ($listdata as $key=>$data){
                $domainIds[] = $data['domain_identity'];
            }
            $domainData = $this->service('PaginationDomain')->getDomainInfoByIds($domainIds);
            if($domainData){
                foreach ($domainData as $key=>$domain){
                    foreach ($listdata as $cnt=>$platform){
                        if($platform['domain_identity'] != $domain['identity']) continue;
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
        $result = file_put_contents($folder.'/platform.json',json_encode($list,JSON_UNESCAPED_UNICODE));
    }
}