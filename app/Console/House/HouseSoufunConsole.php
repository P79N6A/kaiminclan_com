<?php
class HouseSoufunConsole extends Console {
	
	private $url = 'https://cq.newhouse.fang.com/house/s/b9{__PAGE__}/';
	public function fire(){
			
			$url = $this->url;
			$url = str_replace('{__PAGE__}',1,$url);
		
			$sourceText = $this->helper('curl')->init($url)->data($param)->fetch();
			
			list(,$text) = explode('<div class="nl_con clearfix" ctm-data="lplist" id="newhouse_loupai_list">',$sourceText);
			list($text) = explode('<div class="page"',$sourceText);
			
			var_dump($text); die();
			preg_match_all('/<h3>(.*)<\/h3>/',$text,$match);
			
			list(,$linkList) = $match;
			
			if(!$linkList){
				$this->info('没有匹配的数据');
			}
			
			foreach($linkList as $key=>$title){
				$this->info($title);
			}
			
			//var_dump($match); die();
		
	}
}