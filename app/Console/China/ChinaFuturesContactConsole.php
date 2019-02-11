<?php
/**
 *
 * 大宗商品合约切换
 *
 */
 
class ChinaFuturesContactConsole extends Console {
	
	
	
	public function fire(){
		$where = array();
		$where['status'] = 0;
		
		$listdata = $this->service('MaterialProduct')->getProductList($where);
		if($listdata['total'] < 1){
			$this->info('没有数据');
		}
		
		
		$year = date('Y');
		$month = date('m');
		$month = intval($month);
		
		$contractYear = substr($year,2,2);
		$this->info($currentYear);
		
		$where = $symbolList = array();
		
		$dateline = $this->getTime();
		$subscriberId = $this->getUID();
		
		foreach($listdata['list'] as $key=>$product){
			if(!$product['contract_month']){
				continue;
			}
			$contractList = explode(',',$product['contract_month']);
			foreach($contractList as $cnt=>$contractMonth){
				
				if($month >= $contractMonth){
					
					$newContractMonth = $contractMonth;
					if($newContractMonth < 10){
						$newContractMonth = '0'.$newContractMonth;
					}
				
					$start_date = 0;
					$stop_date = strtotime(($year+2).'-'.$newContractMonth.'-'.$product['last_trade_day']);
					$week = date('w',$stop_date);
					switch($week){
						case 6:
							$stop_date = $stop_date+(60*60*24*2);
							break;
						case 0:
							$stop_date = $stop_date+(60*60*24);
						break;
					}
					
					$symbol = $product['code'].($contractYear+1).$newContractMonth;
					
					$where['symbol'] = $symbol;
					$count = $this->model('MaterialContract')->where($where)->count();
					if($count){
						continue;
					}
					
					
					
					
					$symbolList['product_identity'][] = $product['identity'];
					$symbolList['symbol'][] = $symbol;
					$symbolList['dateline'][] = $dateline;
					$symbolList['start_date'][] = $start_date;
					$symbolList['stop_date'][] = $stop_date;
					$symbolList['subscriber_identity'][] = $subscriberId;
					$symbolList['lastupdate'][] = $dateline;
					$symbolList['sn'][] = $this->get_sn();
					
				}
				
				if($contractMonth < 10){
					$contractMonth = '0'.$contractMonth;
				}
			
				$start_date = 0;
				$stop_date = strtotime(($year+1).'-'.$contractMonth.'-'.$product['last_trade_day']);
				$week = date('w',$stop_date);
				switch($week){
					case 6:
						$stop_date = $stop_date+(60*60*24*2);
						break;
					case 0:
						$stop_date = $stop_date+(60*60*24);
					break;
				}
				
				$symbol = $product['code'].$contractYear.$contractMonth;
				
				$where['symbol'] = $symbol;
				$count = $this->model('MaterialContract')->where($where)->count();
				if($count){
					continue;
				}
				
				
				
				
				$symbolList['product_identity'][] = $product['identity'];
				$symbolList['symbol'][] = $symbol;
				$symbolList['dateline'][] = $dateline;
				$symbolList['start_date'][] = $start_date;
				$symbolList['stop_date'][] = $stop_date;
				$symbolList['subscriber_identity'][] = $subscriberId;
				$symbolList['lastupdate'][] = $dateline;
				$symbolList['sn'][] = $this->get_sn();
			
			}
			//$this->info($product['contract_month']);
		}
		
		if(count($symbolList)){
			$this->model('MaterialContract')->data($symbolList)->addMulti();
		}
		
	}
	
}
