<?php
/**
 *
 * 债券编辑
 *
 * 20180301
 *
 */
class BondSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bondId'=>array('type'=>'digital','tooltip'=>'债券ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'catalogue_identity'=>array('type'=>'string','tooltip'=>'目录'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bondId = $this->argument('bondId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($bondId){
			$this->service('DebentureBond')->update($setarr,$bondId);
		}else{
			
			if($this->service('DebentureBond')->checkBondTitle($setarr['title'])){
				
				$this->info('债券已存在',4001);
			}
			
			$this->service('DebentureBond')->insert($setarr);
		}
	}
}
?>