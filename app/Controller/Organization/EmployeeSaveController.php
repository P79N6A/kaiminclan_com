<?php
/**
 *
 * 职员编辑
 *
 * 20180301
 *
 */
class EmployeeSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'employeeId'=>array('type'=>'digital','tooltip'=>'职员ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'页面ID'),
			'company_identity'=>array('type'=>'digital','tooltip'=>'单位','default'=>0),
			'quarters_identity'=>array('type'=>'digital','tooltip'=>'岗位'),
			'department_identity'=>array('type'=>'digital','tooltip'=>'部门'),
			'position_identity'=>array('type'=>'digital','tooltip'=>'职位'),
			'fullname'=>array('type'=>'string','tooltip'=>'姓名'),
			'sexuality'=>array('type'=>'digital','tooltip'=>'性别'),
			'idcard'=>array('type'=>'idcard','tooltip'=>'身份证'),
			'province_district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'area_district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'county_district_identity'=>array('type'=>'digital','tooltip'=>'地区','default'=>0),
			'address'=>array('type'=>'string','tooltip'=>'地址'),
			'mobile'=>array('type'=>'mobile','tooltip'=>'联系电话'),
			'email'=>array('type'=>'email','tooltip'=>'电子邮件'),
			'wechat'=>array('type'=>'string','tooltip'=>'微信'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$employeeId = $this->argument('employeeId');
		
		$setarr = array(
			'company_identity' => $this->argument('company_identity'),
			'quarters_identity' => $this->argument('quarters_identity'),
			'department_identity' => $this->argument('department_identity'),
			'position_identity' => $this->argument('position_identity'),
			'fullname' => $this->argument('fullname'),
			'sexuality' => $this->argument('sexuality'),
			'idcard' => $this->argument('idcard'),
			'province_district_identity' => $this->argument('province_district_identity'),
			'area_district_identity' => $this->argument('area_district_identity'),
			'county_district_identity' => $this->argument('county_district_identity'),
			'address' => $this->argument('address'),
			'mobile' => $this->argument('mobile'),
			'email' => $this->argument('email'),
			'wechat' => $this->argument('wechat'),
			'remark' => $this->argument('remark')
		);
		
		if($employeeId){
			$this->service('OrganizationEmployee')->update($setarr,$employeeId);
		}else{
			
			$this->service('OrganizationEmployee')->insert($setarr);
		}
	}
}
?>