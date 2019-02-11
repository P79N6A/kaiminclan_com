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

class TigerConsoel extends Console {
	public function fire(){
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

class quotattion extends TigerInterface {
	//获取市场状态
	public function getMarketStatus(){
	}
	//获取K线数据
	public function getKLine($period,$symbol,$beginTime){
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
	
	public function getIsbn(){
		$accountId = 'DU575569';
		$response = $this->_execute('order_no',array('account'=>$accountId));
		return $response['orderId'];
	}
	protected function _execute($method,$data){
		$data['account']  = $this->account;
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
		$response = $this->curl()->Fetch();
		if(!$response){
			$this->info('shibai ');
		}
		$output = json_decode($response,true);
		if(isset($output['code'])){
			$output = $output['data'];
		}
		return $output;
	}
	private function makeSign(){
		return 'QwM4MCdffJ5WK59f+dbFvKMn5Qqw2A5GTA8g0XIAp/Fsvb5fbZUwYzxjznx0jO7VO9Npbzd+ywR6VrMz4liblTMPGDvDnPJP0rGUVF+xbj/3MBr3vFZ25XheyjfHIpP6f+qhNkn9KdFsviohZAWeplkYjV+OyxwMQmpnkP/vll4=';
	}
}