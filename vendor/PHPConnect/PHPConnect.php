<?php
class PHPConnect {
	
	private $driver ;
	
	public function init($driver,$appkey,$secret,$redirect_uri){
		$driverFile = __ROOT__.'/vendor/PHPBamboo/extend/PHPConnect/driver/'.(ucfirst($driver)).'Connect.php';
		
		if(!is_file($driverFile)){
			throw new  Exception('登录插件（'.$driver.'）不存在',2100);
		}
		
		include_once $driverFile;
		
		$driver = (ucfirst($driver)).'Connect';
		
		$this->driver = new $driver;
		$this->driver->init($appkey,$secret,$redirect_uri);
		return $this;
	}
	
	public function login(){
		$this->driver->login();
	}
	
	public function userinfo(){
		$this->driver->userinfo();
	}
}