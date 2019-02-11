<?php
/**
 *
 * 知识编辑
 *
 * 20180301
 *
 */
class KnowhowSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'knowhowId'=>array('type'=>'digital','tooltip'=>'知识ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'知识ID'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$knowhowId = $this->argument('knowhowId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($knowhowId){
			$this->service('KnowledgeKnowhow')->update($setarr,$knowhowId);
		}else{
			if($this->service('KnowledgeKnowhow')->checkKnowhowTitle($setarr['title'])){
				$this->info('此知识已存在',40012);
			}
			$this->service('KnowledgeKnowhow')->insert($setarr);
		}
	}
}
?>