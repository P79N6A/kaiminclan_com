<?php
/**
 *
 * 评论编辑
 *
 * 互动
 *
 * 20180301
 *
 */
class ImpressionSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'impressionId'=>array('type'=>'digital','tooltip'=>'印象ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$impressionId = $this->argument('impressionId');
		$title = $this->argument('title');
		
		
		$impressionNewData = array(
			'title' => $this->argument('title')
		);
		
		if($impressionId){
			$impressionData = $this->service('InteractionImpression')->getImpressionBase($impressionId);
			if(!$impressionData){
				$this->info('修改的标签不存在',50002);
			}
			$this->service('InteractionImpression')->update($impressionNewData,$impressionId);
		}else{
			$impressionData = $this->service('InteractionImpression')->checkImpression($impressionNewData['title']);
			if($impressionData){
				$this->info('此印象不存在',50001);
			}
			$this->service('InteractionImpression')->insert($commentNewData);
		}
		
	}
}
?>