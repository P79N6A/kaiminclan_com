<?php
/**
 *
 * 除权出息
 *
 *  情报
 *  证券
 * @author kaimin.clan kaimin.clan@gmail.com
 * @link www.kaiminclan.com
 */
class EliminationChinaStockConsole extends Console {
	
	
	/**
	 *
	 1.送股时：除权价格=(除权日前一天收盘价)÷( 1+送股率)。
	 2.有偿配股时；除权价格=( 除权日前一天收盘价+配股价×配股率)÷（1+配股率）。
	 3.送股与有偿配股相结合时： 除权价=除权日前一天收盘价=配股价×配股率÷（1+送股率+配股率）
	 4.除权和除息同时进行，计算公式为：当日除权除息报价=（前一日收盘价-股息金额+配股价×配股率）÷（1+配股率+送股率）
	 */
	
	public function fire(){
		
		
		$start = $_SERVER['argv'][2];
		$start = intval($start) <1 ?1:$start;
		$perpage = 5;
		$order = 'identity desc';
		$where['without_date'] = array('>',strtotime('-1 day'));
		
		$listdata = $this->service('SecuritiesDividend')->getDividendList($where,$start,$perpage,$order);
		if($listdata['total'] < 1){
			$this->info('没有数据');
		}
		
		foreach($listdata['list'] as $key=>$data){
			$this->info(date('Ymd',$data['without_date']));
		}
		
	}
	
	/**
	 *
	 * @$close double 收盘价
	 * @$cash double 现金
	 * @$bonus double 送股
	 * @$debt double 转股
	 * @$quotas array 配股
	 *
	 * @return $price double
	 */
	private function getNewPrice($close,$cash,$bonus,$debt,$quotas){
		$price = 0;
		
		$price = ($close-$cash+$quotas['price']*$quotas['weight']);
		$leftVal = $close;
		if($cash > 0){
			$leftVal = $leftVal-$cash;
		}
		if(isset($quotas['weight']) && isset($quotas['price'])){
			$leftVal = $leftVal+$quotas['price']*$quotas['weight'];
		}
		
		$rightVal = 1;
		if(isset($quotas['weight'])){
			$rightVal = $rightVal+$quotas['weight'];
		}
		if($debt > 0){
			$rightVal = $rightVal+$debt;
		}
		if($bonus > 0){
			$rightVal = $rightVal+$bonus;
		}
		
		return round($leftVal/$rightVal,2);
	}
}