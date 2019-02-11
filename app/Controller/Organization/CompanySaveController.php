<?php
/**
 *
 * 单位编辑
 *
 * 20180301
 *
 */
class CompanySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'companyId'=>array('type'=>'digital','tooltip'=>'单位ID','default'=>0),
			'company_identity'=>array('type'=>'digital','tooltip'=>'上级单位','default'=>0),
			'continent_district_identity'=>array('type'=>'digital','tooltip'=>'州','default'=>0),
			'region_district_identity'=>array('type'=>'digital','tooltip'=>'区域','default'=>0),
			'country_district_identity'=>array('type'=>'digital','tooltip'=>'国家','default'=>0),
			'motion_identity'=>array('type'=>'digital','tooltip'=>'类型'),
			'scale_identity'=>array('type'=>'digital','tooltip'=>'级别'),
			'artificial'=>array('type'=>'string','tooltip'=>'法人','length'=>80),
			'telephone'=>array('type'=>'telephone','tooltip'=>'联系电话','length'=>80),
			'email'=>array('type'=>'email','tooltip'=>'电子邮件','length'=>80),
			'website'=>array('type'=>'string','tooltip'=>'网站','length'=>80),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'register_address'=>array('type'=>'string','tooltip'=>'注册地址','length'=>80),
			'office_address'=>array('type'=>'string','tooltip'=>'办公地址','length'=>80),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'区域','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$companyId = $this->argument('companyId');
		$attachment_identity = $this->argument('attachment_identity');
		
		$setarr = array(
			'company_identity' => $this->argument('company_identity'),
			'continent_district_identity' => $this->argument('continent_district_identity'),
			'motion_identity' => $this->argument('motion_identity'),
			'scale_identity' => $this->argument('scale_identity'),
			'region_district_identity' => $this->argument('region_district_identity'),
			'country_district_identity' => $this->argument('country_district_identity'),
			'register_address' => $this->argument('register_address'),
			'artificial' => $this->argument('artificial'),
			'telephone' => $this->argument('telephone'),
			'email' => $this->argument('email'),
			'website' => $this->argument('website'),
			'office_address' => $this->argument('office_address'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		$setarr['qualification_attachment_identity'] = json_decode($qualification_attachment_identity);
		
		if($companyId){
			$this->service('OrganizationCompany')->update($setarr,$companyId);
		}else{
			
			if($this->service('OrganizationCompany')->checkCompanyTitle($setarr['title'],$setarr['company_identity'])){
				
				$this->info('单位已存在',4001);
			}
			
			$this->service('OrganizationCompany')->insert($setarr);
		}
	}
}
?>