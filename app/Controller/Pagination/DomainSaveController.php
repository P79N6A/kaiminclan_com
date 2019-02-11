<?php
/**
 *
 * 域名编辑
 *
 * 20180301
 *
 */
class DomainSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'domainId'=>array('type'=>'digital','tooltip'=>'域名ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'code'=>array('type'=>'string','tooltip'=>'域名','length'=>200),
			'folder'=>array('type'=>'string','tooltip'=>'目录','length'=>200,'default'=>''),
			'icp'=>array('type'=>'string','tooltip'=>'备案号','length'=>200),
			'content'=>array('type'=>'doc','tooltip'=>'介绍','length'=>2000,'default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$domainId = $this->argument('domainId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'icp' => $this->argument('icp'),
			'content' => $this->argument('content'),
			'folder' => $this->argument('folder'),
			'code' => $this->argument('code'),
			'remark' => $this->argument('remark')
		);
		
		
		if($domainId){
			$this->service('PaginationDomain')->update($setarr,$domainId);
		}else{
			
			if($this->service('PaginationDomain')->checkDomainTitle($setarr['code'])){
				
				$this->info('域名已存在',4001);
			}
			$this->service('FoundationDomain')->insert($setarr);
		}
	}
}
?>