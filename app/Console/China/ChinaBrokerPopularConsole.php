<?php
class ChinaBrokerPopularConsole extends Console {
	
	protected $api = 'http://feed.mix.sina.com.cn/api/roll/get?pageid=186&lid=1746&num=10&page={__PAGE__}&callback=feedCardJsonpCallback&_=1522556035617';
	
	public function fire(){
		
		
		for($i=461; $i> 0;$i++){
			$url = str_replace('{__PAGE__}',$i,$this->api);
			
			$sourceData = $this->loadUrlData($url);
			if(!$sourceData){
				continue;
			}
			
			$sourceData = str_replace('try{feedCardJsonpCallback(','',$sourceData);
			$sourceData = str_replace('}catch(e){};','',$sourceData);
			var_dump($sourceData); die();
		}
	}
}