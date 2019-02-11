<?php
/**
 *
 * 大宗商品合约行情
 0：豆粕连续，名字
1：145958，不明数字（难道是数据提供商代码？）
2：3170，开盘价
3：3190，最高价
4：3145，最低价
5：3178，昨日收盘价 （2013年6月27日）
6：3153，买价，即“买一”报价
7：3154，卖价，即“卖一”报价
8：3154，最新价，即收盘价
9：3162，结算价
10：3169，昨结算
11：1325，买  量
12：223，卖  量
13：1371608，持仓量
14：1611074，成交量
15：连，大连商品交易所简称`
16：豆粕，品种名简称
17：2013-06-28，日期
 *
 */
 
class ChinaStockSinaRealtimeConsole extends Console {
	
	protected $api = 'http://hq.sinajs.cn/list=';
	
	public function fire(){
		
		
		$week = date('w');
		if(in_array($week,array(0,6))){
			$this->info('周末');exit();
		}
		$d = date('md');
		if(in_array($d,array('0617','0618','0616','0922','0923','0924','101','102','103','104','105','106','107'))){
			$this->info('假日');exit();
		}
		
		
		$start = 1;
		$perpage = 100;
		while(true){
			$where = array(
				'exchange_identity'=array(1,2)
			);
			$listdata = $this->service('SecuritiesStock')->getStockList($where,$start,$perpage);
			if(!$listdata['list']){
				break;
			}
			
			
			foreach($listdata['list'] as $key=>$stock){
				$this->info($stock['symbol']);
				$url = $this->api.((substr($stock['symbol'],0,1) == '6'?'sh':'sz').$stock['symbol']);
				$data = $this->loadUrlData($url);
				
				$quoationData = explode(',',$data);
				if(count($quoationData) < 2){
					continue;
				}
				$this->info($contract['symbol']);
				
				list(,$open,,$close,$high,$low,,,$valume,$traderTotal) = $quoationData;
				if($open == 0){
					continue;
				}
				
				
				$this->service('QuotationStock')->symbol($stock['identity'])->open($open)->low($low)->high($high)->close($close)->valume($valume)->amount($traderTotal)->add();
				$this->service('SecuritiesStock')->update(array('univalent'=>$close),$stock['identity']);
				sleep(1);
				
			}
			$start ++;
		}
		
	}
	
}
