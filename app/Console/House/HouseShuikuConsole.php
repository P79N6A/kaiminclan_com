<?php
class HouseShuikuConsole extends Console {
	
	private $url = 'http://www.shuikult.net/html/meida/list_1_1.html';
	public function fire(){
			
			$url = $this->url;
		
			$sourceText = $this->helper('curl')->init($url)->data($param)->fetch();
			list(,$text) = explode('<div class="listl list2">',$sourceText);
			list($text) = explode('<div class="listr">',$sourceText);
			
			preg_match_all('/<h3>(.*)<\/h3>/',$text,$match);
			
			list(,$linkList) = $match;
			
			if(!$linkList){
				continue;
			}
			
			foreach($linkList as $key=>$title){
				$this->info($title);
			}
			
			//var_dump($match); die();
		
	}
}