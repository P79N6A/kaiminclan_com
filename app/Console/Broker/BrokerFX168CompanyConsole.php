<?php
class BrokerFX168CompanyConsole extends Console {
	
	public function fire(){
		
		$url = 'https://brokers.fx678.com/remTraderApi?page={__page__}&num=50';
		
		$maxPage = 3;
		
		for($i=1;$i<=$maxPage;$i++){
			
			$url = str_replace('{__page__}',$i,$url);
			$this->info($url);
			$content = $this->helper('curl')->init($url)->fetch();
			if(!$content){
				$this->info('failed');
			}
			var_dump($content);
			$this->error('到这里了');
		}
	}
}
?>