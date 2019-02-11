<?php
/***
 * 经营分析
 * 证券-中国区
 http://emweb.securities.eastmoney.com/PC_HSF10/BusinessAnalysis/BusinessAnalysisAjax?code=sh600884
 */
class ChinaStockBusinessRealtimeConsole extends Console {
	
	const CHINA_STOCK_BUSINESS_URL  = 'http://emweb.securities.eastmoney.com/PC_HSF10/BusinessAnalysis/BusinessAnalysisAjax?code=';
	protected function setting(){
		return array(
			'start'=>array('type'=>'digital','tooltip'=>'','default'=>1),
			'perpage'=>array('type'=>'digital','tooltip'=>'','default'=>10)
		);
	}
	public function fire(){
		
		
		
		$where = array(
			'business_identity'=>0
		);
		$count = $this->model('SecuritiesBusiness')->where($where)->count();
		
		$totalPage = ceil($count/50);
		
		
		$begin = 1;
		
		
		$limit = 50;
		for($start=$begin;$start<=$totalPage;$start++){
			$listdata = $this->model('SecuritiesBusiness')->where($where)->limit($start,$limit,$count)->select();
			if(!$listdata){
				$this->error('没有数据');
			}
			foreach($listdata as $key=>$data){
				$this->info($data['title'].'>>'.$start.'>>'.$totalPage);
				$subWhere = array(
					'title'=>$data['title']
				);
				
				$businessId = 0;
				$businessData = $this->model('SecuritiesStockBusiness')->where($subWhere)->find();
				if($businessData){
					$businessId = $businessData['identity'];
				}else{
					 $businessId = $this->model('SecuritiesStockBusiness')->data(array(
						'sn'=>$this->get_sn(),
						'title'=>$data['title'],
						'lastupdate'=>$this->getTime(),
						'dateline'=>$this->getTime(),
					 ))->add();
				}
				$this->model('SecuritiesBusiness')->data(array('business_identity'=>$businessId))->where(array('identity'=>$data['identity']))->save();
				
				
			}
		}
	}
}