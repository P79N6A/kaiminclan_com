<?php
/**
 *
 * 货币编辑
 *
 * 20180301
 *
 */
class CurrencySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'currencyId'=>array('type'=>'digital','tooltip'=>'货币ID','default'=>0),
			'code'=>array('type'=>'letter','tooltip'=>'编码'),
			'title'=>array('type'=>'string','tooltip'=>'简称','length'=>80),
			'continent_district_identity'=>array('type'=>'digital','tooltip'=>'州','default'=>0),
			'region_district_identity'=>array('type'=>'digital','tooltip'=>'区域','default'=>0),
			'country_district_identity'=>array('type'=>'digital','tooltip'=>'国家','default'=>0),
			'fullname'=>array('type'=>'string','tooltip'=>'全称','length'=>120),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$currencyId = $this->argument('currencyId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'continent_district_identity' => $this->argument('continent_district_identity'),
			'region_district_identity' => $this->argument('region_district_identity'),
			'country_district_identity' => $this->argument('country_district_identity'),
			'fullname' => $this->argument('fullname'),
			'remark' => $this->argument('remark')
		);
		
		if($currencyId){
			$this->service('ForeignCurrency')->update($setarr,$currencyId);
		}else{
			
			if($this->service('ForeignCurrency')->checkCurrencyTitle($setarr['title'])){
				
				$this->info('货币已存在',4001);
			}
			
			$this->service('ForeignCurrency')->insert($setarr);
		}
	}
}
?>