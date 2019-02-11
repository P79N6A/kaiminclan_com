<?php
/**
 *
 * 评论赞
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
			'commentId'=>array('type'=>'digital','tooltip'=>'评论ID','default'=>0),
			'id'=>array('type'=>'digital','tooltip'=>'数据ID'),
			'idtype'=>array('type'=>'digital','tooltip'=>'数据类型'),
			'comment'=>array('type'=>'doc','tooltip'=>'内容'),
			'attachmentId'=>array('type'=>'digital','tooltip'=>'附件','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$commentId = $this->argument('commentId');
		
		$commentData = $this->service('InteractionComment')->getCommentBase($commentId);
		if(!$commentData){
			$this->info('回复的留言不存在',50001);
		}
		
		$this->service('InteractionComment')->adjustCommentFabulous($commentData['theme_identity'],$commentId,1);
	}
}
?>