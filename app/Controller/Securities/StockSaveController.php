<?php
/**
 *
 * 证券编辑
 *
 * 20180301
 *
 */
class StockSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'stockId'=>array('type'=>'digital','tooltip'=>'证券ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
			'symbol'=>array('type'=>'string','tooltip'=>'编码'),
			'ipo_date'=>array('type'=>'date','tooltip'=>'上市时间','format'=>'dateline'),
            'exchange_identity'=>array('type'=>'digital','tooltip'=>'交易所','default'=>0),
			'first_industry_identity'=>array('type'=>'digital','tooltip'=>'行业','default'=>0),
			'second_industry_identity'=>array('type'=>'digital','tooltip'=>'行业','default'=>0),
			'third_industry_identity'=>array('type'=>'digital','tooltip'=>'行业','default'=>0),
            'fourth_industry_identity'=>array('type'=>'digital','tooltip'=>'行业','default'=>0),
			'fullname'=>array('type'=>'string','tooltip'=>'全称'),
            'continent_district_identity'=>array('type'=>'digital','tooltip'=>'区域'),
            'region_district_identity'=>array('type'=>'digital','tooltip'=>'区域','default'=>0),
            'country_district_identity'=>array('type'=>'digital','tooltip'=>'区域','default'=>0),
			'registered_address'=>array('type'=>'string','tooltip'=>'注册地址'),
			'office_address'=>array('type'=>'string','tooltip'=>'办公地址'),
            'business'=>array('type'=>'string','tooltip'=>'业务范围'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$stockId = $this->argument('stockId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'symbol' => $this->argument('symbol'),
			'ipo_date' => $this->argument('ipo_date'),
			'first_industry_identity' => $this->argument('first_industry_identity'),
			'second_industry_identity' => $this->argument('second_industry_identity'),
			'third_industry_identity' => $this->argument('third_industry_identity'),
            'fourth_industry_identity' => $this->argument('fourth_industry_identity'),
			'fullname' => $this->argument('fullname'),
            'continent_district_identity' => $this->argument('continent_district_identity'),
            'region_district_identity' => $this->argument('region_district_identity'),
            'country_district_identity' => $this->argument('country_district_identity'),
            'exchange_identity' => $this->argument('exchange_identity'),
			'registered_address' => $this->argument('registered_address'),
            'office_address' => $this->argument('office_address'),
            'business' => $this->argument('business'),
			'remark' => $this->argument('remark'),
		);
		
		
		if($stockId){
			$this->service('SecuritiesStock')->update($setarr,$stockId);
		}else{

			$stockId = $this->service('SecuritiesStock')->insert($setarr);
		}

		$this->assign('stockId',$stockId);
	}
}
?>