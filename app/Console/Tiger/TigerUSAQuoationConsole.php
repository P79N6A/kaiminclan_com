<?php

/**

环境初始化

拉取行情数据

策略应用

开仓

止损追踪

平仓

结束

*/

class TigerHongKongQuoationConsole extends Console {
	public function fire(){
		list(,,$start) = $_SERVER['argv'];
		$start = intval($start);
		$start = $start < 1 ? 1:$start;
		$perpage = 300;

		$week = date('w');
		if(in_array($week,array(0,6))){
			$this->info('周末');exit();
		}
		$d = date('md');
		if(in_array($d,array('0617','0618','0616','0922','0923','0924','101','102','103','104','105','106','107'))){
			$this->info('假日');exit();
		}
		$curHour = date('Hi');
		if(substr($curHour,0,1) == 0){
			$curHour = substr($curHour,1);
		}
		if($curHour < 900){
			//$this->info('未开盘');exit();
		}
		if($curHour > 1530){
			//$this->info('收盘');exit();
		}
		
		$this->info($start);
		$where = array('exchange_identity'=>array(3,4));
		
		$listdata = $this->service('SecuritiesStock')->getStockList($where,$start,$perpage,'identity desc');
		if($listdata['total'] < 1){
			$this->info('没有数据12');
			exit();
		}
		if($start > ceil($listdata['total']/$perpage)){
			$this->info('exit');
			exit();
		}
		
		$quota  = new quotation();
		foreach($listdata['list'] as $key=>$stock){
			$this->info($stock['symbol']);
			$symbolData = $quota->getDaily('day',$stock['symbol'],date('Ymd'));
			if(isset($symbolData['items'])){
				continue;
			}
			$table = 'usa_stock_minute_'.date('Y_W');
			
				
			foreach($symbolData['items'] as $key=>$symbol){
				$setarr = array(
					'id'=>$stock['identity'],
					'cycle'=>$symbol['time'],
					'open'=>$symbol['open'],
					'low'=>$symbol['low'],
					'high'=>$symbol['high'],
					'close'=>$symbol['close'],
					'amount'=>$symbol['close'],
					'valume'=>$symbol['volume'],
					'ema'=>$powerData['ema'],
					'wma'=>$powerData['wma'],
					'slow'=>$oscillatorData['slow'],
					'fast'=>$oscillatorData['fast'],
					'signal'=>$oscillatorData['signal'],
				);
			}
			$this->model('QuotationMarket')->subtable($table)->data($setarr)->add();
		}
	}
}

class account extends TigerInterface {
	//获取合约信息
	public function geta(){
	}
	//获取持仓
	public function getPosition(){
		$param = array(
			 'sec_type'=> 'STK',
		);
		$this->_execute('positions',$param);
	}
	//获取资产
	public function getAssets(){
		$this->_execute('assets',array());
	}
	//获取指定订单
	public function getOrderByTicket($ticket){
		$param = array(
			 'id'=> $ticket,
		);
		$this->_execute('orders',$param);
	}
	//获取订单
	public function getOrders(){
		$param = array(
			 'sec_type'=> 'STK',
		);
		$this->_execute('orders',$param);
	}
	//已成交订单列表
	public function getCompletedOrderddList(){
		$param = array(
			 'sec_type'=> 'STK',
		);
		$this->_execute('filled_orders',$param);
	}
	//待成交订单列表
	public function getWaitOrderdd(){
		$param = array(
			 'sec_type'=> 'STK',
		);
		$this->_execute('active_orders',$param);
	}
	//已撤销订单列表
	public function getRevokeOrder(){
		$param = array(
			 'sec_type'=> 'STK',
		);
		$this->_execute('inactive_orders',$param);	
	}
}

class quotation extends TigerInterface {
	//获取市场状态
	public function getMarketStatus(){
	}
	
	public function getDaily($symbol,$startTime){
		return $this->getKline('day',$symbol,$startTime);
	}
	
	public function getFiveMintue($symbol,$startTime){
		return $this->getKline('5min',$symbol,$startTime);
	}
	//获取K线数据
	public function getKline($period,$symbol,$beginTime){
		$param = array(
			 'period'=> $period,
			 'symbols'=> $symbol,
			 'begin_time'=>$beginTime
		);
		$this->_execute('kline',$param);	
	}
}

class Order extends TigerInterface {
	private $account = 'DU575569';
	
	
	public function send($symbol,$price,$quantity){
		$param = array(
			 'order_id'=>$this->getIsbn(),
			 'symbol'=> $symbol,
			 'sec_type'=> 'STK',
			 'market'=> 'US',
			 'currency'=> 'USD',
			 'action'=>'BUY',
			 'order_type'=>'LMT',
			 'limit_price'=> $price,
			 'total_quantity'=> $quantity,
			 'time_in_force'=> 'DAY',
			 'outside_rth'=> false
		);
		$this->_execute('place_order',$param);
	}
	public function cannel($ticket){
		$param = array(
			 'id'=> $ticket
		);
		$this->_execute('cancel_order',$param);
	}
	public function modify($ticket,$price,$quantity){
		$param = array(
			'id'=>$ticket,
			'order_type'=>'LMT',
			'limit_price'=> $price,
			'total_quantity'=> $quantity,
			'time_in_force'=> 'DAY'
		);
		$this->_execute('place_order',$param);
	}
}

class TigerInterface {
	public function getUsMarketStatus(){
		$param = array(
			'market'=>'US',
		);
		$this->_execute('market_state',$param);
	}
	public function getChinaMarketStatus(){
		$param = array(
			'market'=>'CN',
		);
		$this->_execute('market_state',$param);
	}
	public function getHongKongMarketStatus(){
		$param = array(
			'market'=>'HK',
		);
		$this->_execute('market_state',$param);
	}
	public function getUSAMarketStatus(){
		$param = array(
			'market'=>'US',
		);
		$this->_execute('place_order',$param);
	}
	public function getIsbn(){
		$accountId = 'DU575569';
		$response = $this->_execute('order_no',array('account'=>$accountId));
		return $response['orderId'];
	}
	protected function _execute($method,$data){
		$data['account']  = $this->account;
		$url = 'https://openapi-sandbox.itiger.com/gateway';
		$param = array(
			'tiger_id'=>1,
			'charset'=>'UTF-8',
			'sign_type'=>'RSA',
			'version'=>1.0,
			'method'=>$method,
			'biz_content'=>json_encode($data),
			'timestamp'=>date('Y-m-d H:i:s'),
			'sign'=>$this->makeSign()
		);
		
		$ch = curl_init();
		// 2. 设置选项，包括URL
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_HEADER,0);
		$headers = array('Content-Type:application/json; charset=utf-8');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名 
		 // 3. 执行并获取HTML文档内容
		 $response = curl_exec($ch);
		 if($response === FALSE ){
			var_dump(curl_error($ch));
		 }
		 // 4. 释放curl句柄
		 curl_close($ch);
		 $response = json_decode($response,true);
		if(!isset($response['code'])){
			var_dump(curl_error($ch));
		}
		if($response['code'] != 0){
			die($response['message']);
		}
		return $response['data'];
	}
	private function makeSign(){
		return 'QwM4MCdffJ5WK59f+dbFvKMn5Qqw2A5GTA8g0XIAp/Fsvb5fbZUwYzxjznx0jO7VO9Npbzd+ywR6VrMz4liblTMPGDvDnPJP0rGUVF+xbj/3MBr3vFZ25XheyjfHIpP6f+qhNkn9KdFsviohZAWeplkYjV+OyxwMQmpnkP/vll4=';
	}
}