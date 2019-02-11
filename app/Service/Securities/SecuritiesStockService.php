<?php
/**
 *
 * 合作伙伴
 *
 * 新闻
 *
 */
class SecuritiesStockService extends Service
{
	
	/**
	 *
	 * 证券信息
	 *
	 * @param $field 证券字段
	 * @param $status 证券状态
	 *
	 * @reutrn array;
	 */
	public function getStockList($where,$start,$perpage,$order = 'identity desc',$field = '*'){
		
		$count = $this->model('SecuritiesStock')->where($where)->count();
		if($count){
			$handle = $this->model('SecuritiesStock')->where($where)->field($field);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$exchangeIds = $industryIds = $districtIds = array();
			foreach($listdata as $key=>$data){
				$symbol = $data['symbol'];
				if(is_numeric($symbol)){
					switch(strlen($symbol)){
						case 1:$symbol = '00000'.$symbol; break;
						case 2:$symbol = '0000'.$symbol; break;
						case 3:$symbol = '000'.$symbol; break;
						case 4:$symbol = '00'.$symbol; break;
					}
				}
				$listdata[$key]['symbol'] = $symbol;
				$industryIds[] = $data['first_industry_identity'];
				$industryIds[] = $data['second_industry_identity'];
				$industryIds[] = $data['third_industry_identity'];
				$industryIds[] = $data['fourth_industry_identity'];
				$exchangeIds[] = $data['exchange_identity'];
				$districtIds[] = $data['continent_district_identity'];
				$districtIds[] = $data['region_district_identity'];
				$districtIds[] = $data['country_district_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>SecuritiesStockModel::getStatusTitle($data['status'])
				);
			}
			if($districtIds){			
				$districtData = $this->service('GeographyDistrict')->getDistrictInfo($districtIds);
			}
			
			if($industryIds){			
				$industryData = $this->service('SecuritiesIndustry')->getIndustryInfo($industryIds);
			}
			if($exchangeIds){	
				$exchangeData = $this->service('IntercalateExchange')->getExchangeInfo($exchangeIds);
			}
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['industry'] = array(
					'first'=>isset($industryData[$data['first_industry_identity']])?$industryData[$data['first_industry_identity']]:array(),
					'second'=>isset($industryData[$data['second_industry_identity']])?$industryData[$data['second_industry_identity']]:array(),
					'third'=>isset($industryData[$data['third_industry_identity']])?$industryData[$data['third_industry_identity']]:array(),
					'fourth'=>isset($industryData[$data['fourth_industry_identity']])?$industryData[$data['fourth_industry_identity']]:array(),
				);
				$listdata[$key]['exchange'] = isset($exchangeData[$data['exchange_identity']])?$exchangeData[$data['exchange_identity']]:array();
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
	public function checkStockTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('SecuritiesStock')->where($where)->count();
		}
		return 0;
	}
	
	
	
	/**
	 *
	 * 按证券编号获取证券信息
	 *
	 * @param $symbol 代码
	 *
	 * @reutrn array;
	 */
	public function getStockInfoBySymbol($symbol){
		
		$where = array(
			'symbol'=>$symbol
		);
		
		
		return $this->model('SecuritiesStock')->where($where)->find();
	}
	
	
	
	/**
	 *
	 * 证券信息
	 *
	 * @param $stockId 证券ID
	 *
	 * @reutrn array;
	 */
	public function getStockInfo($stockId,$field = '*'){
		
		$where = array(
			'identity'=>$stockId
		);
		
		$stockData = $this->model('SecuritiesStock')->field($field)->where($where)->select();
		
		return $stockData;
	}
	
	/**
	 *
	 * 删除证券
	 *
	 * @param $stockId 证券ID
	 *
	 * @reutrn int;
	 */
	public function removeStockId($stockId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$stockId
		);
		
		$stockData = $this->model('SecuritiesStock')->where($where)->find();
		if($stockData){
			
			$output = $this->model('SecuritiesStock')->where($where)->delete();

            $this->service('IntercalateExchange')->adjustStockQuantity($stockData['exchange_identity'],-1);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 证券修改
	 *
	 * @param $stockId 证券ID
	 * @param $stockNewData 证券数据
	 *
	 * @reutrn int;
	 */
	public function update($stockNewData,$stockId){
		$where = array(
			'identity'=>$stockId
		);
		
		$stockData = $this->model('SecuritiesStock')->where($where)->find();
		if($stockData){
			
			$stockNewData['lastupdate'] = $this->getTime();
			$this->model('SecuritiesStock')->data($stockNewData)->where($where)->save();
			if($stockData['exchange_identity'] != $stockNewData['exchange_identity']){
                $this->service('IntercalateExchange')->adjustStockQuantity($stockData['exchange_identity'],-1);
                $this->service('IntercalateExchange')->adjustStockQuantity($stockNewData['exchange_identity'],1);
            }
            $this->service('PropertyCapital')->pushStockCapital($stockId,$stockNewData['title'],$stockNewData['first_industry_identity']);
		}
	}
	
	/**
	 *
	 * 新证券
	 *
	 * @param $stockNewData 证券数据
	 *
	 * @reutrn int;
	 */
	public function insert($stockNewData){
		
		$stockNewData['subscriber_identity'] =$this->session('uid');
		$stockNewData['dateline'] = $this->getTime();
			
		$stockNewData['lastupdate'] = $stockNewData['dateline'];
		$stockId = $this->model('SecuritiesStock')->data($stockNewData)->add();
        if($stockId){
            $this->service('IntercalateExchange')->adjustStockQuantity($stockNewData['exchange_identity']);
            $this->service('PropertyCapital')->pushStockCapital($stockId,$stockNewData['title'],$stockNewData['first_industry_identity']);
        }
		return $stockId;
	}
}