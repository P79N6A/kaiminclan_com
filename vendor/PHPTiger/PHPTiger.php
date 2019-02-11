<?php
class PHPTiger {
	private $charset = 'UTF-8';
	
	private $tigerId = 201812254;
	
	private $debug = 0;
	
	public function __construct (){
			
	}
	
	private function getSign($data){
		
		ksort($data);
		
		$query = http_build_query($data);		
		
		//$private_key = file_get_contents("/home/users/xx/test/rsa_private_key.pem");
		$public_key = file_get_contents("/home/users/xx/test/rsa_public_key.pem");
		
		//$pi_key =  openssl_pkey_get_private($private_key);// 可用返回资源id
		$pu_key = openssl_pkey_get_public($public_key);
		
		$encrypted = '';
		$decrypted = '';

		openssl_public_encrypt($data, $encrypted, $pu_key);//公钥加密
		
		return base64_encode($data);
	}
	
	private function execute($method,$data){
		$param = array(
			'tiger_id'=>$this->tigerId,
			'charset'=>$this->charset,
			'sign_type'=>'RSA',
			'version'=>'1.0',
			'timestamp'=>date('Y-m-d H:i:s'),
			'method'=>$method,
			'biz_content'=>json_encode($data),
			'sign'=>$this->getSign($data)
		);
		
		$url = 'https://openapi.itiger.com/gateway';
		if($this->debug){
			$url = 'https://openapi-sandbox.itiger.com/gateway	';
		}
		
		$ch = curl_init();
		
		$response = curl_exec($ch);
		
		return json_decode($response);
	}
	
	public function getOrderddNo($account){
		$orderddNo = '';
		
		$api = '';
		
		$response = $this->execute('order_no',$this->getAccount());
		if(strcmp($response['code'],0) === 0){
			$orderddNo = $response['data'];
		}
		
		return $orderddNo;
	}
	
	private function getAccount(){
		return json_encode(array(
			'account'=>$account
		));
	}
	/***
	 * 创建订单
	 */
	public function createOrder($symbol){
		$orderddId = '';
		
		$api = '';
		
		$param = $this->getAccount();
		$param['order_id'] = $this->getOrderddId();
		
		//美股
		$param['symbol'] = $symbol;
		$param['sec_type'] = $sec_type;
		$param['market'] = $market;
		$param['currency'] = $currency;
		$param['action'] = $action;
		$param['order_type'] = $order_type;
		$param['limit_price'] = $limit_price;
		$param['total_quantity'] = $total_quantity;
		$param['time_in_force'] = $time_in_force;
		
		//2香港
		if($exhange != 2){
			$param['outside_rth'] = $outside_rth;
		}
		
		
		
		$response = $this->execute('place_order',$this->getAccount());
		if(strcmp($response['code'],0) === 0){
			$orderddId = $response['data'];
		}
		
		return $orderddId;
		
	}
	/***
	 * 修改订单
	 */
	public function changeOrder($orderddId){
		$result = '';
		
		$param = $this->getAccount();
		$param['id'] =  $orderddId;
		$param['order_type'] = $order_type;
		$param['limit_price'] = $limit_price;
		$param['total_quantity'] = $total_quantity;
		$param['time_in_force'] = $time_in_force;
		
		$response = $this->execute('modify_order',$this->getAccount());
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
		
		return $result;
		
	}
	
	
	/***
	 * 取消订单
	 */
	public function cannelOrder($orderddId){
		$result = '';
		
		$param = $this->getAccount();
		$param['id'] =  $orderddId;
		
		$response = $this->execute('cancel_order',$this->getAccount());
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
		
		return $result;
	}
	
	
	
	public function Account(){
		
	}
	
	//获取合约信息
	public function getContract($secType,$currency,$market,$symbol,$expiry,$strike,$right,$exchange){
		$result = '';
		
		$param = $this->getAccount();	
		$param['sec_type'] = $secType;
		$param['currency'] = $currency;
		$param['market'] = $market;
		$param['symbol'] = $symbol;
		$param['expiry'] = $expiry;
		$param['strike'] = $strike;
		$param['right'] = $right;
		$param['exchange'] = $exchange;
		
		$response = $this->execute('contract',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
	
	//获取仓位
	public function getPosition($secType,$currency,$market,$symbol){
		$result = '';
		
		$param = $this->getAccount();
		$param['sec_type'] = $secType;
		$param['currency'] = $currency;
		$param['market'] = $market;
		$param['symbol'] = $symbol;
		
		$response = $this->execute('positions',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
		
	}
	//获取资产
	public function getCapital($segment = false,$market_value = false){
		$result = '';
		
		$param = $this->getAccount();
		$param['market_value'] = $market_value;
		$param['segment'] = $segment;
		
		$response = $this->execute('assets',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
		
	}
	
	public function fetchOrderddById($orderddId){
		$result = '';
		
		$param = $this->getAccount();
		$param['id'] = $orderddId;
		
		$response = $this->execute('orders',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
	
	public function fetchOrderddList($symbol,$secType,$market,$startDate,$stopDate,$states,$limit){
		$result = '';
		
		$param = $this->getAccount();
		$param['symbol'] = $symbol;
		$param['sec_type'] = $secType;
		$param['start_date'] = $startDate;
		$param['end_date'] = $stopDate;
		$param['market'] = $market;
		$param['states'] = $states;
		$param['limit'] = $limit;
		
		$response = $this->execute('orders',$this->getAccount());
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
	
	public function fetchOrderddDealList($symbol,$secType,$startDate,$stopDate){
		$result = '';
		
		$param = $this->getAccount();
		$param['symbol'] = $symbol;
		$param['sec_type'] = $secType;
		$param['start_date'] = $startDate;
		$param['end_date'] = $stopDate;
		
		$response = $this->execute('filled_orders',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
	
	public function fetchOrderddDealList($symbol,$secType,$startDate,$stopDate){
		$result = '';
		
		$param = $this->getAccount();
		$param['symbol'] = $symbol;
		$param['sec_type'] = $secType;
		$param['start_date'] = $startDate;
		$param['end_date'] = $stopDate;
		
		$response = $this->execute('filled_orders',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
	
	public function fetchOrderddActiveList($symbol,$secType,$startDate,$stopDate){
		$result = '';
		
		$param = $this->getAccount();
		$param['symbol'] = $symbol;
		$param['sec_type'] = $secType;
		$param['start_date'] = $startDate;
		$param['end_date'] = $stopDate;
		
		$response = $this->execute('active_orders',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
	
	public function fetchOrderddRevokeList($symbol,$secType,$startDate,$stopDate){
		$result = '';
		
		$param = $this->getAccount();
		$param['symbol'] = $symbol;
		$param['sec_type'] = $secType;
		$param['start_date'] = $startDate;
		$param['end_date'] = $stopDate;
		
		$response = $this->execute('inactive_orders',$param);
		if(strcmp($response['code'],0) === 0){
			$result = $response['data'];
		}
				
		
		return $result;
	}
}