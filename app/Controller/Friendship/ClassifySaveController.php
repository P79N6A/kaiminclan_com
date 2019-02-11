<?php
/**
 *
 * 分类编辑
 *
 * 20180301
 *
 */
class ClassifySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'classifyId'=>array('type'=>'digital','tooltip'=>'分类ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$classifyId = $this->argument('classifyId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
		);
		
		if($classifyId){
			$this->service('FriendshipClassify')->update($setarr,$classifyId);
		}else{
			
			if($this->service('FriendshipClassify')->checkClassifyTitle($setarr['title'])){
				
				$this->info('分类已存在',4001);
			}
			
			$this->service('FriendshipClassify')->insert($setarr);
		}
	}
}
?>