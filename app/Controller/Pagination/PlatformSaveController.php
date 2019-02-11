<?php
/**
 *
 * 平台编辑
 *
 * 20180301
 *
 */
class PlatformSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'platformId'=>array('type'=>'digital','tooltip'=>'平台ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'code'=>array('type'=>'letter','tooltip'=>'主机','length'=>80),
			'domain_identity'=>array('type'=>'digital','tooltip'=>'域名'),
            'role_identity'=>array('type'=>'digital','tooltip'=>'权限','length'=>80),
			'seotitle'=>array('type'=>'string','tooltip'=>'SEO标题','length'=>80,'default'=>''),
			'seokeyword'=>array('type'=>'string','tooltip'=>'SEO关键字','length'=>80,'default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
			'seodescription'=>array('type'=>'string','tooltip'=>'SEO描述','length'=>80,'default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$platformId = $this->argument('platformId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'code' => $this->argument('code'),
			'domain_identity' => $this->argument('domain_identity'),
            'role_identity' => $this->argument('role_identity'),
			'seotitle' => $this->argument('seotitle'),
			'seokeyword' => $this->argument('seokeyword'),
			'seodescription' => $this->argument('seodescription'),
			'remark' => $this->argument('remark')
		);
		
		if($platformId){
			$this->service('PaginationPlatform')->update($setarr,$platformId);
		}else{
			
			if($this->service('PaginationPlatform')->checkPlatformTitle($setarr['code'],$setarr['domain_identity'])){
				
				$this->info('平台已存在',4001);
			}
			
			$this->service('FoundationPlatform')->insert($setarr);
		}
	}
}
?>