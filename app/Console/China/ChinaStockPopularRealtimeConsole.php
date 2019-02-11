<?php
/**
 *
 * 中国区上市公司
 *
 * 舆论
 * 实时
 *
 */
 
class ChinaStockPopularRealTimeConsole extends Console {
	
	//关闭缓存
	protected $allowed_cached = false;
	
	public function fire(){
		
		$url = 'http://roll.finance.sina.com.cn/finance/zq1/ssgs/index_1.shtml';
		echo $url."\r\n";
		
		
		$cacheKey = md5($url.date('YmdH'));
		$content = $this->cache($cacheKey);
		if(!$content){
				$this->info('获取远程数据');
				sleep(3);
			$content = $this->loadUrlData($url);
			if(!$content){
				echo 'failed';
			}
			$this->cache($cacheKey,$content);
		}
		
		$content = mb_convert_encoding($content,'utf8','gb2312');
		
		list(,$content) = explode('<div class="listBlk">',$content);
		list($content) = explode('<div class="MainBtm"></div>',$content);
		
		list(,,$linkList,,$linkTitle) = $this->helper('Html')->fetchLinks($content);
		preg_match_all('/<span>\((.*)\)<\/span>/',$content,$addTimeList);
		list(,$addTimeList) = $addTimeList;
		
		foreach($linkList  as $key=>$link){
			if(strpos($link,'index_') !== false){
				continue;
			}
			echo $link;
			$title = $linkTitle[$key];
			$addTime = $addTimeList[$key];
			if($addTime){
				$addTime = str_replace('日','',$addTime);
				$addTime = str_replace('月','-',$addTime);
				if(strpos($addTime,'年') !== false){
					$addTime = str_replace('年','-',$addTime);
				}else{
					$addTime = date('Y').'-'.$addTime;
				}
			}
			$addTime = strtotime($addTime);
			
			$this->info($linkTitle[$key]);
			
			$where = array();
			$where['title'] = $title;
			$where['release_time'] = $addTime;
			$articleData = $this->model('IntelligenceDocumentation')->where($where)->find();
			
			if($articleData){
				continue;
			}
			
			$catalogueId = 26;
			if(strpos($title,'新股') !== false){
				$catalogueId = 44;
			}
			if(strpos($title,'回购') !== false){
				$catalogueId = 42;
			}
			
			if(strpos($title,'分红') !== false){
				$catalogueId = 43;
			}
			
			if(strpos($title,'增发') !== false){
				$catalogueId = 41;
			}
			if(strpos($title,'明日') !== false){
				$catalogueId = 45;
			}
			if(strpos($title,'公告') !== false){
				$catalogueId = 45;
			}
			
			$articleId = 0;
			$article = array(
				'title'=>$title,
				'catalogue_identity'=>$catalogueId,
				'release_time'=>$addTime,
				'from_url'=>$link,
				'sn'=>$this->get_sn(),
				'dateline'=>$this->getTime(),
				'lastupdate'=>$this->getTime()
			);
			$articleId = $this->service('IntelligenceDocumentation')->insert($article);
			
			$textCacheKey = md5($link);
			$textDetail = $this->cache($textCacheKey);
			if(!$textDetail){
				sleep(3);
				$this->info('获取远程数据');
				$textDetail = $this->loadUrlData($link);
				if(!$textDetail){
					echo $link.'failed';
					die();
				}
				$this->cache($textCacheKey,$textDetail);
			}
			
			$textSource = $textDetail;
			//$textDetail = mb_convert_encoding($textDetail,'utf8','gb2312');
			
			$textDetail = $this->service('SinaFormat')->__toHtml($textDetail);
			
			if(strlen($textDetail) > 1){
			
			
				$this->model('IntelligenceSubstance')->data(array(
					'sn'=>$this->get_sn(),
					'documentation_identity'=>$articleId,
					'title'=>$title,
					'content'=>$textDetail,
					'html'=>(empty($textDetail)?$textSource:$textDetail),
					'indexid'=>1,
					'subscriber_identity'=>$this->getUID(),
					'dateline'=>$this->getTime(),
					'lastupdate'=>$this->getTime()
				))->add();
				
				if(!empty($textDetail)){
					$this->service('IntelligenceDocumentation')->update(array('status'=>0,'substance_num'=>1),$articleId);
				}
			}
			
			echo $articleId.'>>'.$page."/r/n";
			
		}
		$this->finish();
	}
	
	
}