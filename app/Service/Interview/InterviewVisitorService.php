<?php
/**
 *
 * 足迹
 *
 */
class  InterviewVisitorService extends Service {
	



	
	/**
	 *
	 * 评论信息
	 *
	 * @param $field 评论字段
	 * @param $status 评论状态
	 *
	 * @reutrn array;
	 */
	public function getVisitorList($field = 'identity,title',$status = InterviewVisitorModel::SUPPLIER_VisitorING_STATUS_ENABLE){
		
		$thmemeData = $this->model('InterviewVisitor')->where($where)->select();
		
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
	public function getVisitorBaseInfo($id,$idtype){
		
		$thmemeData = array();
		
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype,
		);
		
		return $this->model('InterviewVisitor')->where($where)->find();
	}
	
	/**
	 *
	 * 评论信息
	 *
	 * @param $thmemeId 评论ID
	 *
	 * @reutrn array;
	 */
	public function getVisitorInfo($thmemeId){
		
		$thmemeData = array();
		
		$where = array(
			'identity'=>$thmemeId
		);
		
		$thmemeList = $this->model('InterviewVisitor')->where($where)->select();
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
	public function checkVisitor($id,$idtype){
		if($thmemeName){
			$where = array(
				'id'=>$id,
				'idtype'=>$idtype
			);
			return $this->model('InterviewVisitor')->where($where)->count();
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
	public function removeVisitorId($thmemeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$thmemeId
		);
		
		$thmemeData = $this->model('InterviewVisitor')->where($where)->select();
		if($thmemeData){
			$output = $this->model('InterviewVisitor')->where($where)->delete();
			if($output){
				$this->service('InterviewCommentImpression')->removeAllImpressionByVisitorId($thmemeId);
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
		
		$thmemeData = $this->model('InterviewVisitor')->where($where)->find();
		if($thmemeData){
			
			$thmemeNewData['lastupdate'] = $this->getTime();
			$result = $this->model('InterviewVisitor')->data($thmemeNewData)->where($where)->save();
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
		$thmemeId = $this->model('InterviewVisitor')->data($thmemeNewData)->add();
		
		
	}
}