<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  CivilizationArticleService extends Service {
	
	
	/**
	 *
	 * 收藏列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getArticleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('CivilizationArticle')->where($where)->count();
		if($count){
			$articleHandle = $this->model('CivilizationArticle')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$articleHandle = $articleHandle->limit($start,$perpage,$count);
			}
			$listdata = $articleHandle->select();
			$distinctionIds = array();
			foreach($listdata as $key=>$data){
				$distinctionIds[] = $data['distinction_identity'];
			}
			$distinctionData = $this->service('CivilizationColumn')->getDistinctionInfo($distinctionIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['distinction'] = isset($distinctionData[$data['distinction_identity']])?$distinctionData[$data['distinction_identity']]:array();
			}
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $articleIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getArticleInfo($articleIds){
		$articleData = array();
		
		$where = array(
			'identity'=>$articleIds
		);
		
		$articleList = $this->model('CivilizationArticle')->where($where)->select();
		if($articleList){
			
			if(is_array($articleIds)){
				$articleData = $articleList;
			}else{
				$articleData = current($articleList);
			}
			
			
		}
		
		
		return $articleData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $articleId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeArticleId($articleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$articleId
		);
		
		$articleData = $this->model('CivilizationArticle')->where($where)->count();
		if($articleData){
			
			$output = $this->model('CivilizationArticle')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkArticleMobile($mobile){
		$articleId = array();		
		$where = array(
			'mobile'=>$mobile,
		);
		
		
		return $this->model('CivilizationArticle')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $articleId 收藏ID
	 * @param $articleNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($articleNewData,$articleId){
		$where = array(
			'identity'=>$articleId
		);
		
		$articleData = $this->model('CivilizationArticle')->where($where)->find();
		if($articleData){
			
			if($articleNewData['mobile'] != $articleData['mobile']){
				$isValid = $this->service('AuthoritySubscriber')->changeArticleMobileByClientId($articleId,$articleNewData['mobile']);
				if(!$isValid){
					return -1;
				}
			}
			
			$articleNewData['lastupdate'] = $this->getTime();
			$this->model('CivilizationArticle')->data($articleNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($articleData){
		$dateline = $this->getTime();
		$articleData['subscriber_identity'] = $this->session('uid');
		$articleData['dateline'] = $dateline;
		$articleData['lastupdate'] = $dateline;
			
		$articleId = $this->model('CivilizationArticle')->data($articleData)->add();
		if($articleId){
			$this->service('AuthoritySubscriber')->newArticleUser($articleId,$articleData['mobile'],$articleData['fullname']);
		}
		return $articleId;
	}
}