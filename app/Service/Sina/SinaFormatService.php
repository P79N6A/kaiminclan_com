<?php
class SinaFormatService extends Service {
	public function __toHtml($textDetail){
			
		list(,$textDetail) = explode('<div class="article-content clearfix" id=\'article_content\'>',$textDetail);
		list($textDetail) = explode('<div class="article-bottom clearfix" id=\'article-bottom\'>',$textDetail);	
		
		
		$textDetail = preg_replace("/<div[^>]*>/is", "", $textDetail);
		$textDetail = preg_replace("/<\/div>/is", "", $textDetail);
		
			
		$textDetail = preg_replace("/<div[^>]*>(.*?)<\/div>/is", "", $textDetail);
			
		$removeTags = array(
				'<div class="new_style_article" data-sudaclick="ad_content_top"><p>【线索征集令！】你吐槽，我倾听；您爆料，我报道！在这里，我们将回应你的诉求，正视你的无奈。新浪财经爆料线索征集启动，欢迎广大网友积极“倾诉与吐槽”！爆料联系邮箱：finance_biz@sina.com</p></div>',
				'<div id="script_lodaer"></div>',				'<p>【线索征集令！】你吐槽，我倾听；您爆料，我报道！在这里，我们将回应你的诉求，正视你的无奈。新浪财经爆料线索征集启动，欢迎广大网友积极“倾诉与吐槽”！爆料联系邮箱：finance_biz@sina.com</p>',
				'<br>&nbsp&nbsp&nbsp&nbsp新浪声明：',
				'网友评论',
				'<h3>&gt; 相关专题：</h3>',
				'<p>热点栏目自选股数据中心行情中心资金流向模拟交易</p>',
				'<p>客户端</p>',
				'<h3>&gt; 相关报道：</h3>',
				'<div class="hqimg_related"><div class="to_page">热点栏目自选股数据中心行情中心资金流向模拟交易</div>客户端</div></div>'
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
			$textDetail = $this->helper('Html')->removeHtmlTags('blockquote',$textDetail,false);
			$textDetail = $this->helper('Html')->removeEmptyTag($textDetail);
			//$textDetail = $this->helper('Html')->parse($textDetail);
			return trim($textDetail);
	}
}