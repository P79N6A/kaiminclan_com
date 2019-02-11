<?php
/***
 * 
 *
 *
 {
	 'appId':'wxf9547df650d3776f',
	'timeStamp':'1510563482',
	'nonceStr':'0eqmpc9xeuo3q7e9eoyzevczcb2bac4l',
	'package':'prepay_id=wx20171113165802e889fbe4c40354206013',
	'signType':'MD5',
	'paySign':'BC7BD36412262228A88DCB529F7A3A28'
 }
 */
class jzpay_payment {
	
	private $setting = array();
	
	private $product = array();
	
	const JZPAY_API_DOMAIN = 'https://b.jizhipay.com';
	
	public function init($setting,$product){
		$this->ewmid = $appkey;
		$this->secret = $secret;
		$this->product = $product;
	}
	
	public function getUrl(){
		
		$dateline = time();
		$url = self::JZPAY_API_DOMAIN.'/merchants.php?m=Pay&c=pays&a=foreverpaying';
		$url = self::JZPAY_API_DOMAIN.'/Cashier/pay/Pay.php';
		
		
		
		$param = array(
			'ewmid'=>$this->appkey,
			'mchid'=>'1490603042',//商户号			 //微信支付商户ID
			'key'=>'key',							 //微信支付商户KEY
			
			'goods_name'=>$this->product['title'],
			'goods_price'=>$this->product['univalent'],
			'order'=>$dateline,
			'sign'=>md5($this->product['univalent'].$this->appkey.$dateline),
			'nurl'=>'nurl',
			'openid'=>$this->product['openid'],		//微信用户OPENID
			'appid'=>$this->product['appkey'], 	 	//小程序APPID
			'appSecret'=>$this->product['secret'],	//小程序SECRET
		);
		
		
		$param = array(
			'ewmid'=>$this->appkey,
			'paytype'=>'key',						
			
			'goods_price'=>$this->product['univalent'],
			'order'=>$this->product['order'],
			'sign'=>md5(md5($this->product['univalent'].$this->appkey.$this->product['order'])),
			'rurl'=>'rurl',
			'nurl'=>'nurl',
			'remarks'=>$this->product['remarks'],
		);
		
		$result = $this->push($url,$param);
		
		return '';
	}
	
	public function Result(){
		
	}
	
	private function push($url,$param){
		
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); 			// 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Expect:'
        )); // 解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 	// 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); 			// 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 15); 		// 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); 			// 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); 					// 执行操作
        if (curl_errno($curl)) {
			file_put_contents(__LOG__.'/jzpay_'.date('Ymd').'.log',date('Ymd Hi').' '.curl_error($curl)."\r\n",FILE_APPEND);
        }
        curl_close($curl); 								// 关键CURL会话
        return $tmpInfo; 								// 返回数据
	}
}