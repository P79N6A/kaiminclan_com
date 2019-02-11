<?php
/**
 *
 * 评论删除
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
		
		//5003没有权限
		
		$this->service('InteractionComment')->removeCommentId($commentId);
	}
}
?>