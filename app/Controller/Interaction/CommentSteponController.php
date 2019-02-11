<?php
/**
 *
 * 评论踩
 *
 * 互动
 *
 * 20180301
 *
 */
class CommentSteponController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'commentId'=>array('type'=>'digital','tooltip'=>'评论ID'),
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
		
		$this->service('InteractionComment')->adjustCommentSetupon($commentData['theme_identity'],$commentId,1);
	}
}
?>