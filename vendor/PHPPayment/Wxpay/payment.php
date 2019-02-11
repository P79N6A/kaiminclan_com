<?php
class wxpay_payment {
	
	//商户ID
	private $ewmid = '';
	//密匙
	private $secret = '';
	
	public function init($appkey,$secret){
		$this->ewmid = $appkey;
		$this->secret = $secret;
	}
	
	public function getUrl(){
		return '';
	}
	
	public function Result(){
		
	}
}