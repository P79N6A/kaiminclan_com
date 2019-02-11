<?php
/**
 * 知识库
 *
 * 疾病
 */
class KnowledgeDiseaseConsole extends Console {
	
	protected $api = 'http://zzk.xywy.com/p/{CODE}.html';
	
	protected $catalogueId = 1;

	public function fire(){
		
		$letter = 'abcdefghijklmnopqrstuvwxyz';
		$length = strlen($letter);
		
		$curTime = $this->getTime();
		
		for($i=0;$i<$length;$i++){
			$startCode = substr($letter,$i,1);
			$url = str_replace('{CODE}',$startCode,$this->api);
			$this->info($url);
			$content = $this->loadUrlData($url);
			if(!$content){
				continue;
			}
			$content = mb_convert_encoding ($content,'utf-8','gb2312');
			
			list(,$formatText) = explode('<ul class="ks-zm-list clearfix ">',$content);
			list($formatText,) = explode('<ul class="ks-zm-list clearfix">',$formatText);
			
			$diseaseData = array();
			list(,,$linkList,,$linkTitle) = $this->helper('Html')->fetchLinks($formatText);
			foreach($linkTitle as $key=>$title){
				//var_dump($linkTitle); die();
				$this->info($title);
				$diseaseData['sn'][$key] = $this->get_sn();
				$diseaseData['catalogue_identity'][$key] = $this->catalogueId;
				$diseaseData['title'][$key] = $title;
				$diseaseData['from_url'][$key] = str_replace('_gaishu','_jieshao',$linkList[$key]);
				$diseaseData['dateline'][$key] = $curTime;
				$diseaseData['lastupdate'][$key] = $curTime;
			}
			
			if(!empty($diseaseData)){
				$this->model('KnowledgeKnowhow')->data($diseaseData)->addMulti();
			}
		}
	}
}
?>