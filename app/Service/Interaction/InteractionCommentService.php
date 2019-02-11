<?php
/**
 *
 * 评论
 *
 */
class  InteractionCommentService extends Service {
	
	
	/**
	 *
	 * 调整评论评论数量
	 *
	 * @param $themeId 话题ID
	 * @param $commentId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustCommentNum($themeId,$commentId,$quantity){
		
		$where = array(
			'identity' =>$commentId
		);
		
		if($amount < 0){
			$this->model('InteractionComment')->where($where)->setDec('score',$quantity);
		}else{
			$this->model('InteractionComment')->where($where)->setInc('score',$quantity);
		}
		
		$this->service('InteractionTheme')->adjustThemeCommentNum($themeId,$quantity);
	}
	/**
	 *
	 * 调整评论评分
	 *
	 * @param $themeId 话题ID
	 * @param $commentId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustCommentScore($themeId,$commentId,$quantity){
		
		$where = array(
			'identity' =>$commentId
		);
		
		if($amount < 0){
			$this->model('InteractionComment')->where($where)->setDec('score',$quantity);
		}else{
			$this->model('InteractionComment')->where($where)->setInc('score',$quantity);
		}
		
		$this->service('InteractionTheme')->adjustThemeScore($themeId,$quantity);
	}
	
	/**
	 *
	 * 调整评论好评
	 *
	 * @param $themeId 话题ID
	 * @param $commentId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustCommentFabulous($themeId,$commentId,$quantity){
		
		$where = array(
			'identity' =>$commentId
		);
		
		if($quantity < 0){
			$this->model('InteractionComment')->where($where)->setDec('fabulous',$quantity);
		}else{
			$this->model('InteractionComment')->where($where)->setInc('fabulous',$quantity);
		}
		$this->service('InteractionTheme')->adjustThemeFabulous($themeId,$quantity);
		
	}
	
	/**
	 *
	 * 调整评论差评
	 *
	 * @param $themeId 话题ID
	 * @param $commentId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustCommentSetupon($themeId,$commentId,$quantity){
		
		$where = array(
			'identity' =>$commentId
		);
		
		if($quantity < 0){
			$this->model('InteractionComment')->where($where)->setDec('stepon',$quantity);
		}else{
			$this->model('InteractionComment')->where($where)->setInc('stepon',$quantity);
		}
		$this->service('InteractionTheme')->adjustThemeSetupon($themeId,$quantity);
		
	}

	
	/**
	 *
	 * 评论信息
	 *
	 * @param $status 评论状态
	 *
	 * @reutrn array;
	 */
	public function getAllCommentList($status = InteractionCommentModel::SUPPLIER_CommentING_STATUS_ENABLE){
		
		$where = array(
			'status'=>$status
		);
		
		$commentList = $this->model('InteractionComment')->field($field)->where($where)->select();
		if($commentList){
			
			$attachIds = $subscriberIds = array();
			foreach($commentList as $key=>$comment){
				$subscriberIds[] = $comment['subscriber_identity'];
				$attachIds = array_merge($attachIds,explode(',',$comment['attachment_identity_text']));
			}
			
			$attachData = $this->service('ResourcesAttachment')->getAttachUrl($attachIds);
			$subscriberData = $this->service('ResourcesAttachment')->getSubscriberInfo($subscriberIds);
			
			foreach($commentList as $key=>$comment){
				
				$attachList = array();
				$attachIds = explode(',',$comment['attachment_identity_text']);
				if(count($attachIds)){
					foreach($attachIds as $cnt=>$aid){
						if(isset($attachData[$aid])){
							$attachList[] = $attachData[$aid];
						}
							
					}
				}
				unset($comment[$key]['attachment_identity_text']);
				$commentList[$key]['attach'] = $attachList;
				$subscriber = array();
				if(isset($subscriberData[$comment['subscriber_identity']])){
					$subscriber = $subscriberData[$subscriberData[$comment['subscriber_identity']]];
				}
				
				unset($comment[$key]['subscriber_identity']);
				$commentList[$key]['memdata'] = $subscriber;
				
			}
		}
		
		return $commentList;
	}
	
	/**
	 *
	 * 评论信息
	 *
	 * @param $commentId 评论ID
	 *
	 * @reutrn array;
	 */
	public function getCommentBase($commentId){
		
		$commentData = array();
		
		$where = array(
			'identity'=>$commentId
		);
		
		return $this->model('InteractionComment')->where($where)->find();
	}
	/**
	 *
	 * 检测评论名称
	 *
	 * @param $commentName 评论名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($commentName){
		if($commentName){
			$where = array(
				'title'=>$commentName
			);
			return $this->model('InteractionComment')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除评论
	 *
	 * @param $commentId 评论ID
	 *
	 * @reutrn int;
	 */
	public function removeCommentId($commentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$commentId
		);
		
		$commentData = $this->model('InteractionComment')->where($where)->select();
		if($commentData){
			$output = $this->model('InteractionComment')->where($where)->delete();
			if($output){
				$this->service('InteractionCommentImpression')->removeAllImpressionByCommentId($commentId);
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 评论修改
	 *
	 * @param $commentId 评论ID
	 * @param $commentNewData 评论数据
	 *
	 * @reutrn int;
	 */
	public function update($commentNewData,$commentId){
		$where = array(
			'identity'=>$commentId
		);
		
		$commentData = $this->model('InteractionComment')->where($where)->find();
		if($commentData){
			
			$commentNewData['lastupdate'] = $this->getTime();
			$result = $this->model('InteractionComment')->data($commentNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新评论
	 *
	 * @param $commentNewData 评论信息
	 * @param $themeId 话题ID
	 * @param $thememype 话题类型
	 * @param $impressionId 印象
	 *
	 * @reutrn int;
	 */
	public function insert($commentNewData,$themeId,$themeType,$impressionId = array()){
		
		$themeIdentity = 0; 
		$themeData = $this->service('InteractionTheme')->getThemeBaseInfo($themeId,$themeType);
		if(!$themeData){
			$themeIdentity = $this->service('InteractionTheme')->insert(array('id'=>$themeId,'idtype'=>$themeType));
		}else{
			$themeIdentity = $themeData['identity'];
		}
		
		$commentNewData['subscriber_identity'] =$this->session('uid');		
		$commentNewData['dateline'] = $this->getTime();	
		$commentNewData['theme_identity'] = $themeIdentity;
			
		$commentNewData['lastupdate'] = $commentNewData['dateline'];
		$commentId = $this->model('InteractionComment')->data($commentNewData)->add();
		if($commentId){
			$this->service('InteractionTheme')->adjustThemeCommentNum($themeIdentity,1);
					
			if($impressionId){
				$this->service('InteractionCommentImpression')->updateCommentImpression($themeIdentity,$commentId,$impressionId);
			}
		}
		
	}
}