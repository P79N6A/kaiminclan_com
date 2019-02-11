<?php
/**
 *
 * 分类编辑
 *
 * 20180301
 *
 */
class NationalitySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'nationalityId'=>array('type'=>'digital','tooltip'=>'分类ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$nationalityId = $this->argument('nationalityId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
		);
		
		if($nationalityId){
			$this->service('GeographyNationality')->update($setarr,$nationalityId);
		}else{
			
			if($this->service('GeographyNationality')->checkNationalityTitle($setarr['title'])){
				
				$this->info('分类已存在',4001);
			}
			
			$this->service('GeographyNationality')->insert($setarr);
		}
	}
}
?>