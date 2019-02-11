<?php
/**
 *
 * 印象禁用
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
		
		$impressionData = $this->service('InteractionImpression')->getCommentBase($impressionId);
		if(!$impressionData){
			$this->info('印象不存在',50001);
		}
		if($impressionData['status'] == InteractionImpressionModel::INTERACTION_IMPRESSION_STATUS_ENABLE){
			$this->service('InteractionImpression')->update(array('status'=>InteractionImpressionModel::INTERACTION_IMPRESSION_STATUS_DISABLE),$impressionId);
		}
		
	}
}
?>