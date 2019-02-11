<?php
/**
 *
 * 经纪编辑
 *
 * 20180301
 *
 */
class BrokerSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'brokerId'=>array('type'=>'digital','tooltip'=>'经纪ID','default'=>0),
			'supervise_identity'=>array('type'=>'digital','tooltip'=>'监管机构','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'图片','default'=>0),
			'fullname'=>array('type'=>'string','tooltip'=>'全称'),
			'continent_district_identity'=>array('type'=>'digital','tooltip'=>'地区'),
			'district_identity'=>array('type'=>'digital','tooltip'=>'地区'),
			'district_identity'=>array('type'=>'digital','tooltip'=>'地区'),
			'url'=>array('type'=>'url','tooltip'=>'URL'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$brokerId = $this->argument('brokerId');
		
		$setarr = array(
			'supervise_identity' => $this->argument('supervise_identity'),
			'title' => $this->argument('title'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'fullname' => $this->argument('fullname'),
			'continent_district_identity' => $this->argument('continent_district_identity'),
			'region_district_identity' => $this->argument('region_district_identity'),
			'country_district_identity' => $this->argument('country_district_identity'),
			'url' => $this->argument('url'),
			'remark' => $this->argument('remark')
		);
		
		if($brokerId){
			$this->service('IntercalateBroker')->update($setarr,$brokerId);
		}else{
			
			$this->service('IntercalateBroker')->insert($setarr);
		}
	}
}
?>