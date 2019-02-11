<?php
/**
 *
 * 印象删除
 *
 * 互动
 *
 * 20180301
 *
 */
class CommentSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'impressionId'=>array('type'=>'digital','tooltip'=>'印象ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$impressionId = $this->argument('impressionId');
		
		$commentData = $this->service('InteractionImpression')->getImpressionBase($impressionId);
		if(!$commentData){
			$this->info('印象不存在',50001);
		}
		
		//5003没有权限
		
		$this->service('InteractionImpression')->removeImpressionId($commentId);
	}
}
?>