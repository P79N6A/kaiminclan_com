<?php
class PHPPayment {
	
	private $driver ;
	private $product ;
	
	public function init($driver,$appkey,$secret,$product = array()){
		$driverFile = __ROOT__.'/vendor/PHPBamboo/extend/PHPPayment/'.(ucfirst($driver)).'/payment.php';
		
		if(!is_file($driverFile)){
			throw new  Exception('支付插件（'.$driver.'）不存在'.$driverFile,2100);
		}
		
		include_once $driverFile;
		
		$driver = (ucfirst($driver)).'_payment';
		
		$this->product = $product;
		
		$this->driver = new $driver;
		$this->driver->init($appkey,$secret);
		return $this;
	}
	
	public function getUrl(){
		return $this->driver->getUrl();
	}
	
	
}