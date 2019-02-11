<?php
/**
 *
 * 印象/标签
 * 互动
 */
class  InteractionCommentImpressionService extends Service {
	
	
	/**
	 *
	 * 调整印象使用数量
	 *
	 * @param $commentId 印象ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustImpressionUseNum($impressionId,$quantity = 1){
		
		$where = array(
			'identity' =>$impressionId
		);
		
		if($amount < 0){
			$this->model('InteractionImpression')->where($where)->setDec('comment_num',$quantity);
		}else{
			$this->model('InteractionImpression')->where($where)->setInc('comment_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 印象信息
	 *
	 * @param $field 印象字段
	 * @param $status 印象状态
	 *
	 * @reutrn array;
	 */
	public function getAllImpressionList($field = 'identity,title',$status = InteractionImpressionModel::SUPPLIER_ImpressionING_STATUS_ENABLE){
		
		$where = array(
			'status'=>$status
		);
		
		$impressionData = $this->model('InteractionImpression')->field($field)->where($where)->select();
		
		return $impressionData;
	}
	
	/**
	 *
	 * 印象信息
	 *
	 * @param $id 印象ID
	 * @param $idtype 印象类型
	 *
	 * @reutrn array;
	 */
	public function getImpressionBaseInfo($id,$idtype){
		
		$impressionData = array();
		
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype,
		);
		
		return $this->model('InteractionImpression')->where($where)->find();
	}
	
	/**
	 *
	 * 印象信息
	 *
	 * @param $impressionId 印象ID
	 *
	 * @reutrn array;
	 */
	public function getImpressionInfo($impressionId){
		
		$impressionData = array();
		
		$where = array(
			'identity'=>$impressionId
		);
		
		$impressionList = $this->model('InteractionImpression')->where($where)->select();
		if($impressionList){
			
		}
		
		if(!is_array($impressionId)){
			$impressionData = current($impressionData);
		}
		
		return $impressionData;
	}
	/**
	 *
	 * 检测印象名称
	 *
	 * @param $impressionName 印象名称
	 *
	 * @reutrn int;
	 */
	public function checkImpression($impressionName){
		if($impressionName){
			$where = array(
				'title'=>$impressionName
			);
			return $this->model('InteractionImpression')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除指定印象下所有评分
	 *
	 * @param $impressionId 印象ID
	 *
	 * @reutrn int;
	 */
	public function removeImpressionIdComment($impressionId){
		
		$output = 0;
		
		$where = array(
			'impression_identity'=>$impressionId
		);
		
		$impressionTotal = $this->model('InteractionCommentImpression')->where($where)->count();
		if($impressionTotal){
			$output = $this->model('InteractionCommentImpression')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 删除指定评论下所有评分
	 *
	 * @param $commentId 评论ID
	 *
	 * @reutrn int;
	 */
	public function removeAllImpressionByCommentId($commentId){
		
		$output = 0;
		
		$where = array(
			'comment_identity'=>$commentId
		);
		
		$impressionTotal = $this->model('InteractionCommentImpression')->where($where)->count();
		if($impressionTotal){
			$output = $this->model('InteractionCommentImpression')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 删除指定话题下所有评分
	 *
	 * @param $themeId 话题ID
	 *
	 * @reutrn int;
	 */
	public function removeAllImpressionByThemeId($themeId){
		
		$output = 0;
		
		$where = array(
			'theme_identity'=>$themeId
		);
		
		$impressionTotal = $this->model('InteractionCommentImpression')->where($where)->count();
		if($impressionTotal){
			$output = $this->model('InteractionCommentImpression')->where($where)->delete();
		}
		
		return $output;
	}
	/**
	 *
	 * 印象更新
	 *
	 * @param $themeId 话题ID
	 * @param $commentId 评论ID
	 * @param $impressionId 印象ID
	 *
	 * @reutrn int;
	 */
	public function updateCommentImpression($themeId,$commentId,$impressionId){
		
		$output = 0;
		$themeId = intval($themeId);
		$commentId = intval($commentId);
		
		if(!is_array($impressionId)){
			$impressionId = array($impressionId);
		}
		
		//强制转义，并且去掉0值
		$impressionId = array_filter(array_map('intval',$impressionId));
		if($themeId < 1){
			return -1;
		}
		if($commentId < 1){
			return -2;
		}
		if(count($impressionId) < 1){
			return -3;
		}
		
		$where = array(
			'theme_identity'=>$themeId
		);
		
		$impressionList = $this->model('InteractionCommentImpression')->field('identity,impression_identity,use_num')->where($where)->selelct();
		if($impressionList){
			$commentImpressionIds = array();
			foreach($impressionList as $key=>$data){
				if(in_array($data['impression_identity'],$impressionId)){
					$commentImpressionIds[] = $data['identity'];
					 $removeKey = array_search($data['identity'],$impressionId);
					 if($removeKey){
						 unset($impressionId[$removeKey]);
					 }
				}
			}
			if(count($commentImpressionIds)){
				
				$where = array(
					'identity'=>$commentImpressionIds,
				);
				$this->model('InteractionCommentImpression')->where($where)->setInc('use_num',1);
			}
			
			
		}
		
		if(count($impressionId)){
			$setarr = array();
			$uid = $this->session('uid');
			$uid = intval($uid) < 1?0:$uid;
			$dateline = $this->getTime();
			foreach($impressionId as $key=>$id){
				
				$setarr['theme_identity'][] = $themeId;
				$setarr['comment_identity'][] = $commentId;
				$setarr['impression_identity'][] = $id;
				$setarr['subscriber_identity'][] = $uid;
				$setarr['dateline'][] = $dateline;
				$setarr['lastupdate'][] = $dateline;
				
			}
			
			$this->model('InteractionCommentImpression')->data($setarr)->addMulti();
		}
		
		
		
		return $output;
	}
	
}