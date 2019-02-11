<?php
class ShouchangHuaXiaArticleConsole extends Console {
	
	protected $channelId = 46;
	public function fire(){
		
		
		$start = 102;
		
		list(,,$start,$channleId,$catId) = $_SERVER['argv'];
		$start = intval($start);
		
		$start = $start < 1?102:$start;
		$this->channelId = $channelId < 1?46:$channelId;
		$catId = $catId < 1?22:$catId;
		
		$cnt = 1;
		$max = 80;
		
		//9基础资料
		//22考古研究
		//2藏趣逸闻
		//7拍卖动态
		//10真假辩伪
		//23海外新闻
		
		while($start){
		
			$url = 'http://news.cang.com/info/list-'.$catId.'-'.$start.'.html';
			echo $url."\r\n";
			
			if($cnt > $max){
				break;
			}
			
			$cnt++;
			
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
			
			list(,$content) = explode('<div class="newslist">',$content);
			list($content) = explode('<div id="AspNetPager2" class="pages1">',$content);
			$content = strtolower($content);
			
			list(,,$linkList,,$linkTitle) = $this->helper('Html')->fetchLinks($content);
			
			
			foreach($linkList  as $key=>$link){
				echo $link;
				$title = $linkTitle[$key];
				
				$this->info($linkTitle[$key]);
				
				$where = array();
				$where['title'] = $title;
				$articleData = $this->model('IntelligenceDocumentation')->where($where)->find();
				
				
				$articleId = 0;
				if(!$articleData){
				
					$article = array(
						'title'=>$title,
						'catalogue_identity'=>$this->channelId,
						'release_time'=>$addTime,
						'from_url'=>$link,
						'sn'=>$this->get_sn(),
						'dateline'=>$this->getTime(),
						'lastupdate'=>$this->getTime()
					);
					$articleId = $this->model('IntelligenceDocumentation')->data($article)->add();
				}else{
					$articleId = $articleData['identity'];
				}
				
				$textCacheKey = md5($link);
				$textDetail = $this->cache($textCacheKey);
				if(!$textDetail){
					sleep(3);
					$this->info('获取远程数据');
					$textDetail = $this->loadUrlData($link);
					if(!$content){
						echo 'failed';
					}
					$this->cache($textCacheKey,$textDetail);
				}
				
				$textSource = $textDetail;
				
				list(,$textDetail) = explode('<dd id="main_content">',$textDetail);
				list($textDetail) = explode('<div class="relevant clearfix">',$textDetail);		
				
				$textDetail = preg_replace("/<div[^>]*>(.*?)<\/div>/is", "", $textDetail);
				
				
				$textDetail = $this->helper('Html')->removeJavascript($textDetail);
				$textDetail = $this->helper('Html')->removeStyle($textDetail);
				$textDetail = $this->helper('Html')->removeNotes($textDetail);
				$textDetail = $this->helper('Html')->removeHref($textDetail);
				$textDetail = $this->helper('Html')->removeFont($textDetail);
				$textDetail = $this->helper('Html')->removeSpan($textDetail);
				$textDetail = $this->helper('Html')->removeHtmlTags('center',$textDetail,false);
				$textDetail = $this->helper('Html')->removeEmptyTag($textDetail);
				$textDetail = $this->helper('Html')->parse($textDetail);
				
				$textDetail = mb_convert_encoding($textDetail,'utf8','gb2312');
				$this->model('IntelligenceSubstance')->where(array('documentation_identity'=>$articleId))->delete();
				
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
					$this->model('IntelligenceDocumentation')->data(array('status'=>0,'substance_num'=>1))->where(array('identity'=>$articleId))->save();
				}
				
				$this->info($articleId.'>>'.strlen($textDetail).'>>'.$start);
				
			}
			$start--;
		}
	}
}
?>