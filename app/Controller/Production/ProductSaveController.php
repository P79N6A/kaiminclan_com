<?php
/**
 *
 * 产品编辑
 *
 * 20180301
 *
 */
class ProductSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'productId'=>array('type'=>'digital','tooltip'=>'产品ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'项目'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'架构图'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$productId = $this->argument('productId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'subject_identity' => $this->argument('subject_identity'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'content' => $this->argument('content')
		);
		
		if($productId){
			$this->service('ProductionProduct')->update($setarr,$productId);
		}else{
			
			if($this->service('ProductionProduct')->checkProductTitle($setarr['title'],$setarr['subject_identity'])){
				
				$this->info('产品已存在',4001);
			}
			
			$this->service('ProductionProduct')->insert($setarr);
		}
	}
}
?>