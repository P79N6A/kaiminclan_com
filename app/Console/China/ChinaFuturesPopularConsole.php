<?php
/**
 *
 * 期货
 *
 * 舆论
 * 实时
 *
 */
 
class ChinaFuturesPopularConsole extends Console {
	
	//关闭缓存
	protected $allowed_cached = false;
	
	public function fire(){
		
		for($i=519;$i > 400;$i++){
		
		$url = 'http://roll.finance.sina.com.cn/finance/qh/qsyw/index_'.$i.'.shtml';
		echo $url."\r\n";
		
		$this->info('获取远程数据');
		sleep(3);
		$content = $this->loadUrlData($url);
		if(!$content){
		echo 'failed';
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
				if(!$textDetail){
					echo 'failed';
				}
				$this->cache($textCacheKey,$textDetail);
			}
			
			//$sourceText = $textDetail = mb_convert_encoding($textDetail,'utf8','gb2312');
			$sourceText = $textDetail;
				
			//list(,$textDetail) = explode('<div class="blkContainerSblkCon" id="artibody">',$textDetail);
			//list(,$textDetail) = explode('<div class="article-content clearfix" id=\'article_content\'>',$textDetail);
			var_dump($textDetail,$textCacheKey); die();
			list($textDetail) = explode('<div class="article-bottom clearfix" id=\'article-bottom\'>',$textDetail);
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
									
				
			$where = array();
			$where['documentation_identity'] = $articleId;
			$articleData = $this->model('IntelligenceSubstance')->where($where)->find();
			if($articleData){
				$where = array();
				$where['identity'] = $articleData['identity'];
				$this->model('IntelligenceSubstance')->data(array(
					'content'=>$textDetail,
					'html'=>$sourceText,
					'lastupdate'=>$this->getTime()
				))->where($where)->save();
			}else{
				$this->model('IntelligenceSubstance')->data(array(
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
			
			echo $articleId.'>>'.$page."/r/n";
			
		}
		}
	}
	
	
}