<?php
/**
 *
 * 数据
 *
 * 页面
 *
 */
class PaginationDomainService extends Service
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
	public function getDomainList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PaginationDomain')->where($where)->count();
		if($count){
            $handle = $this->model('PaginationDomain')->where($where);
            if($order){
                $handle ->order($order);
            }
            if($perpage){
                $handle ->limit($start,$perpage,$count);
            }
            $listdata = $handle->select();

            foreach ($listdata as $key=>$data){
                $listdata[$key]['status'] = array(
                    'value'=>$data['status'],
                    'label'=>PaginationDomainModel::getStatusTitle($data['status'])
                );
            }
		}
		return array('total'=>$count,'list'=>$listdata);
	}

	public function getAllDomain(){
	    $where = array(
	        'status'=>PaginationDomainModel::PAGINATION_BLOCK_STATUS_ENABLE
        );

	    $list = $this->model('PaginationDomain')->field('identity,title,code')->where($where)->select();

	    return $list;
    }
	
	/**
	 *
	 * 条目信息
	 *
	 * @param $domainId 条目ID
	 *
	 * @reutrn array;
	 */
	public function getDomainInfo($domainId){
		
		$where = array(
			'identity'=>$domainId
		);
		
		$domainData = $this->model('PaginationDomain')->where($where)->select();
		
		return $domainData;
	}
	
	public function getDomainIdByCode($domainCode){
		
		$domainId = 0;
        $where = array(
            'code'=>$domainCode
        );

        $domainData = $this->model('PaginationDomain')->where($where)->find();
		if($domainData){
			$domainId = $domainData['identity'];
		}
        return $domainId;
	}


	public function getDomainInfoByIds($domainId){

        $where = array(
            'identity'=>$domainId,
            'status'=>PaginationDomainModel::PAGINATION_BLOCK_STATUS_ENABLE
        );

        $domainData = $this->model('PaginationDomain')->where($where)->select();

        return $domainData;
    }
	
	/**
	 *
	 * 删除条目
	 *
	 * @param $domainId 条目ID
	 *
	 * @reutrn int;
	 */
	public function removeDomainId($domainId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$domainId
		);
		
		$domainData = $this->model('PaginationDomain')->where($where)->find();
		if($domainData){
			
			$output = $this->model('PaginationDomain')->where($where)->delete();
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
	public function removeBlockIdAllDomain($blockId){
		
		$output = 0;
		
		$where = array(
			'block_identity'=>$blockId
		);
		
		$domainData = $this->model('PaginationDomain')->where($where)->find();
		if($domainData){
			
			$output = $this->model('PaginationDomain')->where($where)->delete();
		    $this->release();
            $this->service('PaginationPlatform')->release();
            $this->service('PaginationCatalogue')->release();
            $this->service('PaginationPage')->release();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 条目修改
	 *
	 * @param $domainId 条目ID
	 * @param $domainNewData 条目数据
	 *
	 * @reutrn int;
	 */
	public function update($domainNewData,$domainId){
		$where = array(
			'identity'=>$domainId
		);
		
		$domainData = $this->model('PaginationDomain')->where($where)->find();
		if($domainData){
			
			$domainNewData['lastupdate'] = $this->getTime();
			$this->model('PaginationDomain')->data($domainNewData)->where($where)->save();
		    $this->release();
            $this->service('PaginationPlatform')->release();
            $this->service('PaginationCatalogue')->release();
            $this->service('PaginationPage')->release();
		}
	}
	
	/**
	 *
	 * 新条目
	 *
	 * @param $domainNewData 条目数据
	 *
	 * @reutrn int;
	 */
	public function insert($domainNewData){

        $domainNewData['sn'] = $this->get_sn();
		$domainNewData['subscriber_identity'] =$this->session('uid');
		$domainNewData['dateline'] = $this->getTime();
			
		$domainNewData['lastupdate'] = $domainNewData['dateline'];
		$domainId = $this->model('PaginationDomain')->data($domainNewData)->add();
		if($domainId){
		    $this->release();
            $this->service('PaginationPlatform')->release();
            $this->service('PaginationCatalogue')->release();
            $this->service('PaginationPage')->release();
        }
		return $domainId;
	}

    public function release(){
        $list = array();

        $folder = __DATA__.'/json/pagination';
        if(!is_dir($folder)){
            mkdir($folder,0777,1);
        }

        $result = file_put_contents($folder.'/domain.json',json_encode($list,JSON_UNESCAPED_UNICODE));
    }
}