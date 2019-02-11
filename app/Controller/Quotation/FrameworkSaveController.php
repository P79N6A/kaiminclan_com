<?php
/**
 *
 * 目录编辑
 *
 * 20180301
 *
 */
class FrameworkSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'exchangeId'=>array('type'=>'digital','tooltip'=>'目录ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'string','tooltip'=>'介绍','length'=>5000),
			'continent_district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'region_district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'country_district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'address'=>array('type'=>'string','tooltip'=>'地址'),
			'url'=>array('type'=>'url','tooltip'=>'网站地址'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$exchangeId = $this->argument('exchangeId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'continent_district_identity' => $this->argument('continent_district_identity'),
			'region_district_identity' => $this->argument('region_district_identity'),
			'country_district_identity' => $this->argument('country_district_identity'),
			'address' => $this->argument('address'),
			'url' => $this->argument('url'),
			'remark' => $this->argument('remark')
		);
		
		if($exchangeId){
			$this->service('QuotationFramework')->update($setarr,$exchangeId);
		}else{
			
			if($this->service('QuotationFramework')->checkFrameworkTitle($setarr['title'])){
				
				$this->info('目录已存在',4001);
			}
			
			$this->service('QuotationFramework')->insert($setarr);
		}
	}
}
?>