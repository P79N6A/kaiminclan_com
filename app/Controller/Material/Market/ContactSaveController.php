<?php
/**
 *
 * 地址编辑
 *
 * 营销
 *
 */
class ContactSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'contactId'=>array('type'=>'digital','tooltip'=>'联系人ID','default'=>0),
			'fullname'=>array('type'=>'string','tooltip'=>'姓名'),
			'telephone'=>array('type'=>'telephone','tooltip'=>'联系电话'),
			'district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'address'=>array('type'=>'string','tooltip'=>'地址信息'),
			'longitude'=>array('type'=>'digital','tooltip'=>'精度','default'=>0),
			'latitude'=>array('type'=>'digital','tooltip'=>'纬度','default'=>0),
			'hospital_identity'=>array('type'=>'digital','tooltip'=>'所在医院','default'=>0),
			'secleted'=>array('type'=>'digital','tooltip'=>'地址默认','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$contactId = $this->argument('contactId');
		$contactInsertData = array(
			'fullname'=>$this->argument('fullname'),
			'telephone'=>$this->argument('telephone'),
			'district_identity'=>$this->argument('district_identity'),
			'address'=>$this->argument('address'),
			'longitude'=>$this->argument('longitude'),
			'latitude'=>$this->argument('latitude'),
			'hospital_identity'=>$this->argument('hospital_identity'),
			'secleted'=>$this->argument('secleted'),
		);
		
		if($contactId){
			$contactData = $this->service('MarketContact')->getContactInfo($contactId);
			if(!$contactData){
				$this->info('联系地址不存在',40002);
			}
			if($contactData['subscriber_identity'] != (int)$this->session('uid')){
				$this->info('谨允许操作自己的地址信息',40003);
			}
			
		}else{
			if($this->service('MarketContact')->checkTelephone($contactId)){
				$this->info('此联系电话已存在',40001);
			}
		}
		
		
		if($contactInsertData['longitude'] < 1 || $contactInsertData['latitude'] < 1){
			$ditrictName = $this->service('FoundationDistrict')->getFullName($contactInsertData['district_identity']);
			$map = $this->service('GeographyCoordinate')->getCoordinate($ditrictName.$contactInsertData['address']);
			
			$contactInsertData['longitude'] = $map->longitude;
			$contactInsertData['latitude'] = $map->latitude;
		}
		
		if($contactId){
			$this->service('MarketContact')->update($contactInsertData,$contactId);
		}else{
			$this->service('MarketContact')->insert($contactInsertData);
		}
		
		
	}
}
?>