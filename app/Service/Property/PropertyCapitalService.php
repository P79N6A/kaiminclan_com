<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  PropertyCapitalService extends Service {
	
	
	/**
	 *
	 * 收藏列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getCapitalList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PropertyCapital')->where($where)->count();
		if($count){
			$capitalHandle = $this->model('PropertyCapital')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$capitalHandle = $capitalHandle->limit($start,$perpage,$count);
			}
			$listdata = $capitalHandle->select();
			$industryIds = $scaleIds = array();
			foreach($listdata as $key=>$data){
				$industryIds[] = $data['industry_identity'];		
				$scaleIds[] = $data['scale_identity'];		
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>PropertyCapitalModel::getStatusTitle($data['status'])
				);
			}
			
			$industryData = $this->service('PropertyIndustry')->getIndustryInfo($industryIds);
			$scaleData = $this->service('PropertyScale')->getScaleInfo($scaleIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['industry'] = isset($industryData[$data['industry_identity']])?$industryData[$data['industry_identity']]:array();	
				$listdata[$key]['scale'] = isset($scaleData[$data['scale_identity']])?$scaleData[$data['scale_identity']]:array();
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $capitalIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getCapitalInfo($capitalIds){
		$capitalData = array();
		
		$where = array(
			'identity'=>$capitalIds
		);
		
		$capitalList = $this->model('PropertyCapital')->where($where)->select();
		if($capitalList){
			
			if(is_array($capitalIds)){
				$capitalData = $capitalList;
			}else{
				$capitalData = current($capitalList);
			}
			
			
		}
		
		
		return $capitalData;
	}

    /**
     *
     * 外汇品种
     *
     * @param $currencyId
     * @param $currencyName
     *
     * @return mixed
     */
	public function pushCurrencyCapital($currencyId,$currencyName){

        $idtype = 1;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$currencyId
        );
        $capitalData = $this->model('PropertyCapital')->where($where)->find();
        if($capitalData){
            return $capitalData['identity'];
        }
        $capitalNewData = array(
            'title'=>'三竹外汇（'.$currencyName.'）投资有限公司',
            'id'=>$currencyId,
            'idtype'=>$idtype,
            'industry_identity'=>5,
            'scale_identity'=>9,
        );
        return $this->insert($capitalNewData);
    }
    public function pushContractCapital($contractId,$contractName,$currencyId){

        $idtype = 1;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$currencyId
        );
		$capitalId = 0;
        $capitalData = $this->model('PropertyCapital')->where($where)->find();
		if($capitalData){
			$capitalId = $capitalData['identity'];
		}

        $idtype = 6;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$contractId
        );
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
        if($capitalData){
            return $capitalData['identity'];
        }

        $capitalNewData = array(
            'title'=>'三竹外汇（'.$contractName.'）投资有限公司',
            'id'=>$contractId,
			'capital_identity'=>$capitalId,
            'idtype'=>$idtype,
            'industry_identity'=>5,
            'scale_identity'=>10,
        );
        return $this->insert($capitalNewData);
    }

    /**
     *
     * 证券目录
     *
     * @param $industryId
     * @param $industryName
     *
     * @return mixed
     */
    public function pushIndustryCapital($industryId,$industryName){

        $idtype = 2;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$industryId
        );
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
        if($capitalData){
            return $capitalData['identity'];
        }
        $capitalNewData = array(
            'title'=>'三竹证券（'.$industryName.'）投资有限公司',
            'id'=>$industryId,
            'idtype'=>$idtype,
            'industry_identity'=>3,
            'scale_identity'=>9,
        );
        return $this->insert($capitalNewData);
    }
    public function pushStockCapital($stockId,$stockName,$industryId){


        $idtype = 2;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$industryId
        );
		$capitalId = 0;
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
		if($capitalData){
			$capitalId = $capitalData['identity'];
		}
		
        $idtype = 5;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$stockId
        );
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
        if($capitalData){
            return $capitalData['identity'];
        }
        $capitalNewData = array(
            'title'=>'三竹证券（'.$stockName.'）投资有限公司',
            'id'=>$stockId,
			'capital_identity'=>$capitalId,
            'idtype'=>$idtype,
            'industry_identity'=>3,
            'scale_identity'=>10,
        );
        return $this->insert($capitalNewData);
    }

    /**
     *
     *  推送大宗商品目录
     *
     * @param $catalogueId
     * @param $catalogName
     *
     * @return mixed
     */
    public function pushCatalogueCapital($catalogueId,$catalogName){

        $idtype = 3;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$catalogueId
        );
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
        if($capitalData){
            return $capitalData['identity'];
        }
        $capitalNewData = array(
            'title'=>'三竹期货（'.$catalogName.'）投资有限公司',
            'id'=>$catalogueId,
            'idtype'=>$idtype,
            'industry_identity'=>6,
            'scale_identity'=>9,
        );
        return $this->insert($capitalNewData);
    }
    public function pushFuturesCapital($futuresId,$futureName,$catalogueId){

        $idtype = 3;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$catalogId
        );
		$capitalId = 0;
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
		if($capitalData){
			$capitalId = $capitalData['identity'];
		}

        $idtype = 4;
        $where = array(
            'idtype'=>$idtype,
            'id'=>$futuresId
        );
        $capitalData = $this->model('PropertyCapital')->field('identity')->where($where)->find();
        if($capitalData){
            return $capitalData['identity'];
        }
        $capitalNewData = array(
            'title'=>'三竹期货（'.$futureName.'）投资有限公司',
			'capital_identity'=>$capitalId,
            'id'=>$futuresId,
            'idtype'=>$idtype,
            'industry_identity'=>6,
            'scale_identity'=>10,
        );
        return $this->insert($capitalNewData);
    }





    /**
	 *
	 * 删除收藏
	 *
	 * @param $capitalId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeCapitalId($capitalId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$capitalId
		);
		
		$listdata = $this->model('PropertyCapital')->where($where)->select();
		if($listdata){
			$scalIds = $industryIds = array();
			foreach($listdata as $key=>$data){
				$industryIds[] = $data['industry_identity'];
				$scalIds[] = $data['scale_identity'];
			}
			$this->service('PropertyIndustry')->adjustCapitalTotal($industryIds,-1);
			$this->service('PropertyScale')->adjustCapitalTotal($scalIds,-1);
			
			$output = $this->model('PropertyCapital')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkCapitalTitle($title){
		$capitalId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('PropertyCapital')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $capitalId 收藏ID
	 * @param $capitalNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($capitalNewData,$capitalId){
		$where = array(
			'identity'=>$capitalId
		);
		
		$capitalData = $this->model('PropertyCapital')->where($where)->find();
		if($capitalData){
			
			
			$capitalNewData['lastupdate'] = $this->getTime();
			$this->model('PropertyCapital')->data($capitalNewData)->where($where)->save();
			if($capitalData['industry_identity'] != $capitalNewData['industry_identity']){
				$this->service('PropertyIndustry')->adjustCapitalTotal($capitalData['industry_identity'],-1);
				$this->service('PropertyIndustry')->adjustCapitalTotal($capitalNewData['industry_identity']);
			}
			if($capitalData['scale_identity'] != $capitalNewData['scale_identity']){
				$this->service('PropertyScale')->adjustCapitalTotal($capitalData['scale_identity'],-1);
				$this->service('PropertyScale')->adjustCapitalTotal($capitalNewData['scale_identity']);
			}
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($capitalData){
		$dateline = $this->getTime();
		$capitalData['subscriber_identity'] = $this->session('uid');
		$capitalData['dateline'] = $dateline;
		$capitalData['lastupdate'] = $dateline;
		$capitalData['sn'] = $this->get_sn();
			
		$capitalId = $this->model('PropertyCapital')->data($capitalData)->add();
		if($capitalId){
			$this->service('PropertyIndustry')->adjustCapitalTotal($capitalData['industry_identity']);
			$this->service('PropertyScale')->adjustCapitalTotal($capitalData['scale_identity']);
			$this->service('MechanismAccount')->newPropertyCapitalAccount($capitalId,$capitalData['title']);
		}
		return $capitalId;
	}
}