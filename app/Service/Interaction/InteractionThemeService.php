<?php
/**
 *
 * 评论
 *
 */
class  InteractionThemeService extends Service {
	
	
	/**
	 *
	 * 调整评论评论数量
	 *
	 * @param $commentId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustThemeCommentNum($thmemeId,$quantity){
		
		$where = array(
			'identity' =>$thmemeId
		);
		
		if($amount < 0){
			$this->model('InteractionTheme')->where($where)->setDec('comment_num',$quantity);
		}else{
			$this->model('InteractionTheme')->where($where)->setInc('comment_num',$quantity);
		}
		
	}
	/**
	 *
	 * 调整评论评分
	 *
	 * @param $thmemeId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustThemeScore($thmemeId,$quantity){
		
		$where = array(
			'identity' =>$thmemeId
		);
		
		if($amount < 0){
			$this->model('InteractionTheme')->where($where)->setDec('score',$amount);
		}else{
			$this->model('InteractionTheme')->where($where)->setInc('score',$amount);
		}
	}
	
	/**
	 *
	 * 调整评论好评
	 *
	 * @param $thmemeId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustThemeFabulous($thmemeId,$quantity){
		
		$where = array(
			'identity' =>$thmemeId
		);
		
		if($quantity < 0){
			$this->model('InteractionTheme')->where($where)->setDec('fabulous',$quantity);
		}else{
			$this->model('InteractionTheme')->where($where)->setInc('fabulous',$quantity);
		}
		
	}
	
	/**
	 *
	 * 调整评论差评
	 *
	 * @param $thmemeId 评论ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustThemeSetupon($thmemeId,$quantity){
		
		$where = array(
			'identity' =>$thmemeId
		);
		
		if($quantity < 0){
			$this->model('InteractionTheme')->where($where)->setDec('stepon',$quantity);
		}else{
			$this->model('InteractionTheme')->where($where)->setInc('stepon',$quantity);
		}
		
	}

	
	/**
	 *
	 * 评论信息
	 *
	 * @param $field 评论字段
	 * @param $status 评论状态
	 *
	 * @reutrn array;
	 */
	public function getAllThemeList($field = 'identity,title',$status = InteractionThemeModel::SUPPLIER_ThemeING_STATUS_ENABLE){
		
		$where = array(
			'status'=>$status
		);
		
		$thmemeData = $this->model('InteractionTheme')->field($field)->where($where)->select();
		
		return $thmemeData;
	}
	
	/**
	 *
	 * 话题信息
	 *
	 * @param $id 话题ID
	 * @param $idtype 话题类型
	 *
	 * @reutrn array;
	 */
	public function getThemeBaseInfo($id,$idtype){
		
		$thmemeData = array();
		
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype,
		);
		
		return $this->model('InteractionTheme')->where($where)->find();
	}
	
	/**
	 *
	 * 评论信息
	 *
	 * @param $thmemeId 评论ID
	 *
	 * @reutrn array;
	 */
	public function getThemeInfo($thmemeId){
		
		$thmemeData = array();
		
		$where = array(
			'identity'=>$thmemeId
		);
		
		$thmemeList = $this->model('InteractionTheme')->where($where)->select();
		if($thmemeList){
			
		}
		
		if(!is_array($thmemeId)){
			$thmemeData = current($thmemeData);
		}
		
		return $thmemeData;
	}
	/**
	 *
	 * 检测评论名称
	 *
	 * @param $id 数据ID
	 * @param $idtype 数据类型
	 *
	 * @reutrn int;
	 */
	public function checkTheme($id,$idtype){
		if($thmemeName){
			$where = array(
				'id'=>$id,
				'idtype'=>$idtype
			);
			return $this->model('InteractionTheme')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除评论
	 *
	 * @param $thmemeId 评论ID
	 *
	 * @reutrn int;
	 */
	public function removeThemeId($thmemeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$thmemeId
		);
		
		$thmemeData = $this->model('InteractionTheme')->where($where)->select();
		if($thmemeData){
			$output = $this->model('InteractionTheme')->where($where)->delete();
			if($output){
				$this->service('InteractionCommentImpression')->removeAllImpressionByThemeId($thmemeId);
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 评论修改
	 *
	 * @param $thmemeId 评论ID
	 * @param $thmemeNewData 评论数据
	 *
	 * @reutrn int;
	 */
	public function update($thmemeNewData,$thmemeId){
		$where = array(
			'identity'=>$thmemeId
		);
		
		$thmemeData = $this->model('InteractionTheme')->where($where)->find();
		if($thmemeData){
			
			$thmemeNewData['lastupdate'] = $this->getTime();
			$result = $this->model('InteractionTheme')->data($thmemeNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新评论
	 *
	 * @param $thmemeNewData 评论信息
	 *
	 * @reutrn int;
	 */
	public function insert($thmemeNewData){
		$thmemeNewData['subscriber_identity'] =$this->session('uid');		
		$thmemeNewData['dateline'] = $this->getTime();
			
		$thmemeNewData['lastupdate'] = $thmemeNewData['dateline'];
		$thmemeId = $this->model('InteractionTheme')->data($thmemeNewData)->add();
		
		
	}
}