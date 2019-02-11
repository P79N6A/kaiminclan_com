<?php
/***
 * 经营分析
 * 证券-中国区
 http://emweb.securities.eastmoney.com/PC_HSF10/BusinessAnalysis/BusinessAnalysisAjax?code=sh600884
 */
class ChinaStockBusinessConsole extends Console {
	
	const CHINA_STOCK_BUSINESS_URL  = 'http://emweb.securities.eastmoney.com/PC_HSF10/BusinessAnalysis/BusinessAnalysisAjax?code=';
	protected function setting(){
		return array(
			'start'=>array('type'=>'digital','tooltip'=>'','default'=>51),
			'perpage'=>array('type'=>'digital','tooltip'=>'','default'=>50)
		);
	}
	public function fire(){
		$start = $this->argument('start');
		$perpage = $this->argument('perpage');
		
		while(true){
			if($start > 100){
				$this->info('最后更新'.$start);
				break;
			}
			$where = array();
			$where['exchange_identity'] = 0;
			$listdata = $this->service('SecuritiesStock')->getStockList($where,$start,$perpage);
			if($listdata['total'] < 1){
				$this->error('没有数据');
			}
			
			foreach($listdata['list'] as $key=>$stock){
				
				$this->info($stock['title']);
				$where = array(
					'stock_identity'=>$stock['identity']
				);
				
				$count = $this->model('SecuritiesBusiness')->where($where)->count();
				if($count){
					$this->info('已完成');
					continue;
				}
				$symbol = $stock['symbol'];			
						
				switch(strlen($symbol)){
					case 1:$symbol = '00000'.$symbol; break;
					case 2:$symbol = '0000'.$symbol; break;
					case 3:$symbol = '000'.$symbol; break;
					case 4:$symbol = '00'.$symbol; break;
				}
				if(substr($symbol,0,1) == 6){
					$symbol = 'sh'.$symbol;
				}else{
					$symbol = 'sz'.$symbol;
				}
				$url = self::CHINA_STOCK_BUSINESS_URL.$symbol;
				$this->info($url);
				$data = $this->loadUrlData($url);
				if(!$data){
					$this->info("未定义");
					continue;
				}
				
				$stockBusinessList = json_decode($data,true);
				$zyfw = $stockBusinessList['zyfw'];
				$jyps = $stockBusinessList['jyps'];
				
				$businessListData = array();
				$curTime = $this->getTime();
				foreach($stockBusinessList['zygcfx'] as $key=>$businessList){
					$this->info($businessList['rq']);
					$date = $businessList['rq'];
					
					$where = array(
						'cycle'=>$date,
						'stock_identity'=>$stock['identity']
					);
					
					$count = $this->model('SecuritiesBusiness')->where($where)->count();
					if($count){
						continue;
					}
					$hyList = $businessList['hy'];
					foreach($hyList as $cp=>$cpData){
						$businessListData['stock_identity'][] = $stock['identity'];
						$businessListData['cycle'][] = $date;
						$businessListData['title'][] = $cpData['zygc'];
						$businessListData['zysr'][] = $cpData['zysr'];
						$businessListData['srbl'][] = $cpData['srbl'];
						$businessListData['zycb'][] = $cpData['zycb'];
						$businessListData['cbbl'][] = $cpData['cbbl'];
						$businessListData['zylr'][] = $cpData['zylr'];
						$businessListData['lrbl'][] = $cpData['lrbl'];
						$businessListData['mll'][] = $cpData['mll'];
						$businessListData['dw'][] = $cpData['dw'];
						$businessListData['subscriber_identity'][] = 1;
						$businessListData['dateline'][] = $curTime;
						$businessListData['lastupdate'][] = $curTime;
					}
					$qyList = $businessList['qy'];
					foreach($qyList as $cp=>$cpData){
						$businessListData['stock_identity'][] = $stock['identity'];
						$businessListData['cycle'][] = $date;
						$businessListData['title'][] = $cpData['zygc'];
						$businessListData['zysr'][] = $cpData['zysr'];
						$businessListData['srbl'][] = $cpData['srbl'];
						$businessListData['zycb'][] = $cpData['zycb'];
						$businessListData['cbbl'][] = $cpData['cbbl'];
						$businessListData['zylr'][] = $cpData['zylr'];
						$businessListData['lrbl'][] = $cpData['lrbl'];
						$businessListData['mll'][] = $cpData['mll'];
						$businessListData['dw'][] = $cpData['dw'];
						$businessListData['subscriber_identity'][] = 1;
						$businessListData['dateline'][] = $curTime;
						$businessListData['lastupdate'][] = $curTime;
					}
					$cpList = $businessList['cp'];
					foreach($cpList as $cp=>$cpData){
						$businessListData['stock_identity'][] = $stock['identity'];
						$businessListData['cycle'][] = $date;
						$businessListData['title'][] = $cpData['zygc'];
						$businessListData['zysr'][] = $cpData['zysr'];
						$businessListData['srbl'][] = $cpData['srbl'];
						$businessListData['zycb'][] = $cpData['zycb'];
						$businessListData['cbbl'][] = $cpData['cbbl'];
						$businessListData['zylr'][] = $cpData['zylr'];
						$businessListData['lrbl'][] = $cpData['lrbl'];
						$businessListData['mll'][] = $cpData['mll'];
						$businessListData['dw'][] = $cpData['dw'];
						$businessListData['subscriber_identity'][] = 1;
						$businessListData['dateline'][] = $curTime;
						$businessListData['lastupdate'][] = $curTime;
					}
				}
				if(!empty($businessListData)){
					$this->model('SecuritiesBusiness')->data($businessListData)->addMulti();
				}
			}
			$start++;
		}
	}
}