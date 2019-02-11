<?php
/**
 *
 * 外汇
 *
 * 舆论
 *
 * 4857之前一场数据
 */
 
class ChinaForexPopularConsole extends Console {
	
	protected $name = '外汇动态';
	
	protected $description = '初始化外汇舆论，定向采集新浪网信息';
	
	public function fire(){
			
	
		list(,,$start,$page) = $_SERVER['argv'];
		$page = intval($start);
		
		$start = $this->getStart();
		if(!$start){
			if(!$page){
				$this->info("未定义开始队列");
			}else{
				$start = $page;
			}
		}
		
		
		
		$offset = -1;
		if(!$this->isLocked()){
			$start--;			
		}else{
			$offset = $this->getOffset();
		}
		
		$start = 530;
		//var_dump($start,$page,'test'); die();
		
			for($page = $start;$page < 1000;$page++){
				//echo $page;
				//continue;
				$this->locked($page);
			
				$url = 'http://roll.finance.sina.com.cn/finance/wh/index_'.$page.'.shtml';
				echo $url."\r\n";
				
				
				$cacheKey = md5($url);
				$content = $this->cache($cacheKey);
				if(!$content){
						$this->info('获取远程列表数据');
						sleep(3);
					$content = $this->loadUrlData($url);
					if(!$content){
						$this->error('FAILED',$content); die();
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
				
				$articleId = 0;
				$article = array(
					'title'=>$title,
					'catalogue_identity'=>39,
					'release_time'=>$addTime,
					'from_url'=>$link,
					'sn'=>$this->get_sn(),
					'dateline'=>$this->getTime(),
					'lastupdate'=>$this->getTime()
				);
				$articleId = $this->model('IntelligenceDocumentation')->data($article)->add();
				
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
				//$textDetail = mb_convert_encoding($textDetail,'utf8','gb2312');
				
				list(,$textDetail) = explode('<div class="article-content clearfix" id=\'article_content\'>',$textDetail);
				list($textDetail) = explode('<div class="article-bottom clearfix" id=\'article-bottom\'>',$textDetail);
				
				$removeTags = array(
					'<div id="script_lodaer"></div>',
					'<br>&nbsp&nbsp&nbsp&nbsp新浪声明：',
					'网友评论',
					'<h3>&gt; 相关专题：</h3>',
					'<h3>&gt; 相关报道：</h3>',
				);
				
				foreach($removeTags as $key=>$tags){
					if(strpos($textDetail,$tags) !== false){
						list($textDetail) = explode($tags,$textDetail);
					}
				}
				
				$textDetail = $this->helper('Html')->removeJavascript($textDetail);
				$textDetail = $this->helper('Html')->removeStyle($textDetail);
				$textDetail = $this->helper('Html')->removeNotes($textDetail);
				$textDetail = $this->helper('Html')->removeHref($textDetail);
				$textDetail = $this->helper('Html')->removeFont($textDetail);
				$textDetail = $this->helper('Html')->removeSpan($textDetail);
				$textDetail = $this->helper('Html')->removeHtmlTags('center',$textDetail,false);
				$textDetail = $this->helper('Html')->removeEmptyTag($textDetail);
				
				
				
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
				
				$this->info($articleId.'>>'.$page);
				
			}
		}
	}
	
}