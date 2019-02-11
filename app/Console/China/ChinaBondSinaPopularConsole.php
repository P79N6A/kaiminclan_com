<?php
/**
 *
 * 中国区债券要闻
 *
 * 舆论
 *
 */
 
class ChinaBondSinaPopularConsole extends Console {
	
	
	
	public function fire(){
			
	
		$start = 468;
		list(,,$start) = $_SERVER['argv'];
		$start = intval($start);
		$start = $start < 1?1:$start;
		
		for($page = $start;$page>0;$page--){
			
			$this->locked($page);
		
			$url = 'http://roll.finance.sina.com.cn/finance/zq2/zsscdt/index_'.$page.'.shtml';
			echo $url."\r\n";
			
			
			$cacheKey = md5($url);
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
				$this->adjustOffset();
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
			
				
				$where = array();
				$where['title'] = $title;
				$where['release_time'] = $addTime;
				$articleData = $this->model('ArticleDocumentation')->where($where)->find();
				if(!$articleData){
					$this->info($linkTitle[$key]);
					$articleId = 0;
					$article = array(
						'title'=>$title,
						'catalogue_identity'=>26,
						'release_time'=>$addTime,
						'from_url'=>$link,
						'sn'=>$this->get_sn(),
						'dateline'=>$this->getTime(),
						'lastupdate'=>$this->getTime()
					);
					$articleId = $this->model('ArticleDocumentation')->data($article)->add();
					if($articleId < 1){
						die();
					}
				}else{
					$articleId = $articleData['identity'];
				}
				
				$this->info($linkTitle[$key]);
				$articleId = 0;
				$article = array(
					'title'=>$title,
					'catalogue_identity'=>38,
					'release_time'=>$addTime,
					'from_url'=>$link,
					'sn'=>$this->get_sn(),
					'dateline'=>$this->getTime(),
					'lastupdate'=>$this->getTime()
				);
				$articleId = $this->model('ArticleDocumentation')->data($article)->add();
				if($articleId < 1){
					die();
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
				$textDetail = mb_convert_encoding($textDetail,'utf8','gb2312');
				
				list(,$textDetail) = explode('<div class="blkContainerSblkCon" id="artibody">',$textDetail);
				if($textDetail){
					list($textDetail) = explode('<div class="blkComment otherContent_01" style="margin-right:0px;margin-left:0;padding-right:0px;">',$textDetail);
				}else{
					list(,$textDetail) = explode('<div class="blkContainerSblkCon BSHARE_POP" id="artibody">',$textDetail);
					if($textDetail){
						list($textDetail) = explode('<div class="guess-view-list clearfix">',$textDetail);
					}else{
						list(,$textDetail) = explode('<div class="article-content clearfix" id=\'article_content\'>',$textDetail);
						if($textDetail){
							list($textDetail) = explode('<div class="article-bottom clearfix" id=\'article-bottom\'>',$textDetail);
						}
					}
				}
				
				
				
				if(strpos($textDetail,'<div id="script_lodaer"></div>') !== false){
					list($textDetail) = explode('<div id="script_lodaer"></div>',$textDetail);
				}
				if(strpos($textDetail,'<br>&nbsp&nbsp&nbsp&nbsp新浪声明：') !== false){
					list($textDetail) = explode('<br>&nbsp&nbsp&nbsp&nbsp新浪声明：',$textDetail);
				}
				if(strpos($textDetail,'网友评论') !== false){
					list($textDetail) = explode('网友评论',$textDetail);
				}
				if(strpos($textDetail,'<h3>&gt; 相关专题：</h3>') !== false){
					list($textDetail) = explode('<h3>&gt; 相关专题：</h3>',$textDetail);
				}
				if(strpos($textDetail,'<h3>&gt; 相关报道：</h3>') !== false){
					list($textDetail) = explode('<h3>&gt; 相关报道：</h3>',$textDetail);
				}
				
				$textDetail = $this->helper('Html')->removeJavascript($textDetail);
				$textDetail = $this->helper('Html')->removeStyle($textDetail);
				$textDetail = $this->helper('Html')->removeNotes($textDetail);
				$textDetail = $this->helper('Html')->removeHref($textDetail);
				$textDetail = $this->helper('Html')->removeFont($textDetail);
				$textDetail = $this->helper('Html')->removeSpan($textDetail);
				$textDetail = $this->helper('Html')->removeHtmlTags('center',$textDetail,false);
				$textDetail = $this->helper('Html')->removeEmptyTag($textDetail);
				
				//var_dump('>>',$textDetail); die();
				
				
				
				$where = array();
				$where['documentation_identity'] = $articleId;
				$articleData = $this->model('ArticleSubstance')->where($where)->find();
				if($articleData){
					$where = array();
					$where['identity'] = $articleData['identity'];
					$this->model('ArticleSubstance')->data(array(
						'content'=>$textDetail,
						'html'=>$sourceText,
						'lastupdate'=>$this->getTime()
					))->where($where)->save();
				}else{
					$this->model('ArticleSubstance')->data(array(
						'sn'=>$this->get_sn(),
						'documentation_identity'=>$articleId,
						'title'=>$title,
						'content'=>$textDetail,
						'html'=>$sourceText,
						'indexid'=>1,
						'subscriber_identity'=>$this->getUID(),
						'dateline'=>$this->getTime(),
						'lastupdate'=>$this->getTime()
					))->add();
				}
				$this->info($articleId.'>>'.$page);
				
			}
			$this->unlock();
		}
		
	}
	
	
}