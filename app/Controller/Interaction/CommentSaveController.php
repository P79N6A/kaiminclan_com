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
			'comment'=>array('type'=>'doc','tooltip'=>'内容','length'=>500),
			'score'=>array('type'=>'digital','tooltip'=>'评分','default'=>0),
			'impressionId'=>array('type'=>'digital','tooltip'=>'印象','default'=>0),
			'attachmentId'=>array('type'=>'digital','tooltip'=>'附件','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$id = $this->argument('id');
		$idtype = $this->argument('idtype');
		$commentId = $this->argument('commentId');
		$impressionId = $this->argument('impressionId');
		
		if($commentId){
			$commentData = $this->service('InteractionComment')->getCommentBase($commentId);
			if(!$commentData){
				$this->info('回复的留言不存在',50001);
			}
			if($commentData['status'] == InteractionCommentModel::INTERACTION_COMMENT_REPLY_DISABLE){
				$this->info('此留言禁止回复',50002);
			}
		}
		
		$commentNewData = array(
			'comment_identity' => $commentId,
			'comment' => $this->argument('comment'),
			'score' => $this->argument('score'),
			'attachment_identity_text' => implode(',',$this->argument('attachmentId'))
		);
		
		$this->service('InteractionComment')->insert($commentNewData,$id,$idtype,$impressionId);
	}
}
?>