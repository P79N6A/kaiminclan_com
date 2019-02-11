<?php
/**
 *
 * 现金流量表
 *
 *  证券板块
 */
class FinanceChinaCashFlowConsole extends Console {
	
	protected $intial =0;
	
	private $api = 'http://money.finance.sina.com.cn/corp/go.php/vFD_CashFlow/stockid/{_SYMBOL_}/ctrl/{_YEAR_}/displaytype/4.phtml';
	
	
	public function fire(){
		
		
		$start = intval($_SERVER['argv'][2]);
		$start = intval($start) <1 ?1:$start;
		$cnt = 0;
		$max = 30;		
		$perpage = 1000;
		
		$where = array(
			'identity'=>array('gt',0),
			'exchange_identity'=>array(1,2)
		);
		
		$count = $this->model('SecuritiesStock')->where($where)->count();
		if(!$count){
			var_dump('no data');
			die();
		}
		
		$listdata = $this->model('SecuritiesStock')->field('identity,title,symbol,ipo_date')->where($where)->limit($start,$perpage,$count)->select();
		if(!$listdata){
			var_dump('no data');
			die();
		}
		foreach($listdata as $key=>$stokcData){
			
			$cnt++;
			if($cnt > $max){
				sleep(5);
				$cnt=0;
			}
		$symbol = $stokcData['symbol'];
		switch(strlen($symbol)){
			case 1:$symbol = '00000'.$symbol; break;
			case 2:$symbol = '0000'.$symbol; break;
			case 3:$symbol = '000'.$symbol; break;
			case 4:$symbol = '00'.$symbol; break;
		}
		$startYear = date('Y');
		$maxYear = $startYear+1;
		
		if($this->intial){
			$startYear = date('Y',$stokcData['ipo_date']-60*60*24*365*3);
			if($startYear < 1991){
				$startYear = 1991;
			}
		}
		
		//$startYear = 1995;
		for($year=$startYear;$year<$maxYear;$year++){
			
			$url = str_replace('{_SYMBOL_}',$symbol,$this->api);
			$url = str_replace('{_YEAR_}',$year,$url);
			echo $url."\r\n";
			
			$cacheKey = md5($url);
			$content = $this->cache($cacheKey);
			if(!$content){
				$content = $this->loadUrlData($url);
				if(!$content){
					echo 'failed';
				}
				$this->cache($cacheKey,$content);
			}
			
			$content = mb_convert_encoding($content,'utf8','gb2312');
		
		
			list(,$resultText) = explode('<table id="ProfitStatementNewTable0" width="100%">',$content);
			list($resultText) = explode('<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table2">',$resultText);
			
			$resultText = $this->helper('Html')->fetchTagData($resultText);
			$offset = 0;
			$length = count($resultText[1]);
			
			$yearList = array();
			$where = array();
			for($s=3;$s<7;$s++){
				$temp = trim(strip_tags($resultText[1][$s]));
				if(strtotime($temp) != false){
					
					$where['symbol_identity'] = $stokcData['identity'];
					$where['revolution'] = strtotime($temp);
					$balanceData = $this->model('StatementsCash')->where($where)->find();
					if($balanceData){
						continue;
					}
					$yearList[] = $temp;
				}
			}
			if(count($yearList) < 1){
				continue;
			}
		
			$startOffset = count($yearList)-1;
			
			$subjectId = 0;
			$start = false;
			$startCnt = -1;
			$subjectTitle = '';
			for($i=$offset; $i<$length;$i++){
				$title = trim(strip_tags($resultText[1][$i]));
				if(empty($title) || $title == '报表日期' || strpos($title,'现金流量表') !== false){
					continue;
				}
				$startOffset = 0;
				$first = substr($title,0,1);
				if($first == '-'){
					$startOffset = 1;
				}
				if(is_numeric(substr($title,$startOffset,1))){
					
					if($start == true){
						$start = false;
						$startCnt = $startOffset;
					}
					
					if($startCnt < 0){
						//$this->info( '未定义时间坐标');
						continue;
					}
					
					$revolution = strtotime($yearList[$startCnt]);
					if(!$revolution){
						//$this->info( '未定义时间');
						continue;
					}
					if($startOffset > 0){
						$startCnt--;
					}
					
					$where = array();
					$where['symbol_identity'] = $stokcData['identity'];
					$where['revolution'] = $revolution;
					$where['subject_identity'] = $subjectId;
					$balanceData = $this->model('StatementsCash')->where($where)->find();
					if($balanceData){
						//$this->info( '已存在的数据');
						continue;
					}
					
					$amount = str_replace(',','',$title);
					$this->model('StatementsCash')->data(array(
						'sn'=>$this->get_sn(),
						'subject_identity'=>$subjectId,
						'symbol_identity'=>$stokcData['identity'],
						'amount'=>$amount,
						'revolution'=>$revolution,
						'subscriber_identity'=>1000,
						'dateline'=>$this->getTime(),
						'lastupdate'=>$this->getTime(),
					))->add();
					$this->info(date('Y-m-d',$revolution).'>>'.$subjectTitle.'>>'.$amount);
				}
				elseif($title == '--'){
					continue;
				}else{
					$start = true;
					$subjectTitle = $title;
					$where = array();
					$where['title'] = $title;
					$subjectData = $this->model('StatementsSubject')->where($where)->find();
					if(!$subjectData){
						$subjectId = $this->model('StatementsSubject')->data(array(
							'title'=>$title,
						))->add();
					}else{
						$subjectId = $subjectData['identity'];
					}
				}
				//echo mb_convert_encoding($title,'gbk')."\r\n"; 
			}
			
		}
		}
	}
}