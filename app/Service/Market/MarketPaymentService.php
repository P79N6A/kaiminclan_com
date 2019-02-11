<?php
/**
 *
 * 支付
 *
 */
class MarketPaymentService extends Service {
	
	/** 配置句柄*/
	
	protected $setting;
	
	//刷卡支付
	const MARKET_PAYMENT_MODE_SWING_CARD = 0;
	//扫码支付
	const MARKET_PAYMENT_MODE_SCAN_CODE = 1;
	//JSAPI支付
	const MARKET_PAYMENT_MODE_JSAPI = 2;
	//H5
	const MARKET_PAYMENT_MODE_H5 = 3;
	/***
	 *
	 * 初始化
	 *
	 *
	 */
	public function init()
	{
		$this->setting = $this->config('pay');
	}
	/**
	 * 
	 * 查询
	 *
	 * 查询提交的支付订单的状态
	 * 
	 * @param $merchantCode 商户订单号
	 * @param $sysCode 系统订单号
	 * 
	 * 
	 */
	public function query($merchantCode = '',$sysCode = ''){
		if(!$sysCode && !$merchantCode){
			return -1;
		}
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Config.php';
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Exception.php';
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Api.php';
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Data.php';
		
		
		$config = (array)$this->setting['weixin'];
		
		$weixin = (array)$this->config('weixin');
		
		WxPayConfig::$MCHID = $config['merchant'];
		WxPayConfig::$KEY = $config['key'];
		
		WxPayConfig::$APPID = $weixin['appkey'];
		WxPayConfig::$APPSECRET = $weixin['secret'];
		
		WxPayConfig::$SSLCERT_PATH = $config['sslcert'];
		WxPayConfig::$SSLKEY_PATH = $config['sslkey'];
		
		
		$input = new WxPayOrderQuery();
	
		if($merchantCode){
			$input->SetOut_trade_no($merchantCode);
		}
		elseif($sysCode){
			$input->SetTransaction_id($sysCode);
		}
		
		return WxPayApi::orderQuery($input);
	}
	/**
	 * 
	 * 付款下单
	 *
	 * 提交支付订单
	 * 
	 * 
	 * @param $code 订单号
	 * @param $amount 金额
	 * @param $type 支付方式
	 * 
	 */
	public function payment($code,$amount,$type = self::MARKET_PAYMENT_MODE_SCAN_CODE){
		if($amount < -1){
			return -1;
		}
		if(!$code){
			return -2;
		}
		
		
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Config.php';
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Exception.php';
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Api.php';
		require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.Data.php';
		
					
		
		$config = (array)$this->setting['weixin'];
		
		$weixin = (array)$this->config('weixin');
		
		WxPayConfig::$MCHID = $config['merchant'];
		WxPayConfig::$KEY = $config['key'];
		
		WxPayConfig::$APPID = $weixin['appkey'];
		WxPayConfig::$APPSECRET = $weixin['secret'];
		
		WxPayConfig::$SSLCERT_PATH = $config['sslcert'];
		WxPayConfig::$SSLKEY_PATH = $config['sslkey'];
		
		
		if($type != self::MARKET_PAYMENT_MODE_SWING_CARD){
			
			//统一下单
			$input = new WxPayUnifiedOrder();
			
			$input->SetBody("test1232");
			$input->SetAttach("test");
			$orderddFlowerCode = $code.round(microtime(true),0);
			$input->SetOut_trade_no($orderddFlowerCode);
			if(defined('__APP_DEBUG__') && __APP_DEBUG__ == TRUE ){
				$amount = 1;
			}
			$amount = str_replace('.','',$amount*100);
			$input->SetTotal_fee($amount);
			$input->SetTime_start(date('YmdHis'));
			$input->SetTime_expire(date('YmdHis', time() + 600));
			
			$input->SetGoods_tag("test");
			$input->SetNotify_url($config['notify']);
			
			//其他
			switch($type){
				case self::MARKET_PAYMENT_MODE_H5:
					require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.H5.php';
					$input->SetTrade_type('MWEB');
					$input->SetSpbill_create_ip(long2ip($this->getClientIp()));
					
					//$order = WxPayApi::unifiedOrder($input);
				
					$tools = new H5ApiPay();
					$result = $tools->GetH5ApiParameters($order);
						
					break;
				case self::MARKET_PAYMENT_MODE_JSAPI:
					require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.JsApiPay.php';
					//JSAPI
					$openid = $this->session('openid');
					if(!$openid){
						$this->log('未定义用户信息');	
					}else{
						$input->SetTrade_type('JSAPI');
						$input->SetOpenid($openid);
						
						$order = WxPayApi::unifiedOrder($input);
						
						
						$tools = new JsApiPay();
						
						$result = $tools->GetJsApiParameters($order);
					}
					break;
				case self::MARKET_PAYMENT_MODE_SCAN_CODE:
					require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.NativePay.php';
					$notify = new NativePay();
					//扫码
					$input->SetTrade_type('NATIVE');
					$input->SetProduct_id('123456789');
					$result = $notify->GetPayUrl($input);
					if($result['return_code'] == 'FAIL'){
						$this->log($result['return_msg']);	
					}
					$result = $result["code_url"];
					break;
			}
		}else{
			//刷卡支付
			require_once __VENDOR__.'/extend/PHPPayment/Wxpay/lib/WxPay.MicroPay.php';
			$input = new WxPayMicroPay();
			$input->SetAuth_code($auth_code);
			$input->SetBody("刷卡测试样例-支付");
			$input->SetTotal_fee($amount);
			$input->SetOut_trade_no(WxPayConfig::$MCHID.date("YmdHis"));
			
			$microPay = new MicroPay();
			$result = $microPay->pay($input);
		}
		
		return array('data'=>$result,'code'=>$orderddFlowerCode);
	}
	/**
	 * 
	 * 退款下单
	 *
	 * 提交支付订单
	 * 
	 * @param $code 订单号
	 * @param $refundAmount 退款金额
	 * @param $orderAmount 订单金额
	 * 
	 */
	public function refund(){
	}
	/**
	 * 
	 * 撤单
	 *
	 * 撤销支付订单
	 * 
	 * @param $code 订单号
	 * 
	 */
	public function revoke(){
	}
}
?>