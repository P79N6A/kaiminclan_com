<?php
/**
 *
 * 地区编辑
 *
 * 20180301
 *
 */
class DistrictSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'districtId'=>array('type'=>'digital','tooltip'=>'地区ID','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'district_identity'=>array('type'=>'digital','tooltip'=>'上级地区','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'fullname'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$districtId = $this->argument('districtId');
		
		$setarr = array(
			'district_identity' => $this->argument('district_identity'),
			'title' => $this->argument('title'),
			'code' => $this->argument('code'),
			'fullname' => $this->argument('fullname'),
			'remark' => $this->argument('remark'),
		);
		
		if($districtId){
			$this->service('GeographyDistrict')->update($setarr,$districtId);
		}else{
			
			if($this->service('GeographyDistrict')->checkDistrictTitle($setarr['title'],$setarr['district_identity'])){
				
				$this->info('地区已存在',4001);
			}
			
			$this->service('GeographyDistrict')->insert($setarr);
		}
	}
}
?>