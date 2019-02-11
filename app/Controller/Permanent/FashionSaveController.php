<?php
/**
 *
 * 分类编辑
 *
 * 20180301
 *
 */
class FashionSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'fashionId'=>array('type'=>'digital','tooltip'=>'分类ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$fashionId = $this->argument('fashionId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($fashionId){
			$this->service('PermanentFashion')->update($setarr,$fashionId);
		}else{
			
			if($this->service('PermanentFashion')->checkFashionTitle($setarr['title'])){
				
				$this->info('分类已存在',4001);
			}
			
			$this->service('PermanentFashion')->insert($setarr);
		}
	}
}
?>