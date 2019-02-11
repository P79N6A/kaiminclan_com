<?php
/**
 *
 * 页面
 *
 *
 */
class ResourcesPageService extends Service
{
	
	/**
	 *
	 * 页面信息
	 *
	 * @param $field 页面字段
	 * @param $status 页面状态
	 *
	 * @reutrn array;
	 */
	public function getPageList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ResourcesPage')->where($where)->count();
		if($count){
			$listdata = $this->model('ResourcesPage')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 页面浏览数
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustPageView($pageId,$quantity = 1){
		
		$where = array(
			'identity' =>$pageId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('ResourcesPage')->where($where)->setInc('view_num',1);
					break;
				case -1:
					$this->model('ResourcesPage')->where($where)->setDec('view_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 短链接
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function getShortUrl(){
	}
	/**
	 *
	 * 页面访问数
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustVisitView($pageId,$quantity = 1){
		
		$where = array(
			'identity' =>$pageId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('ResourcesPage')->where($where)->setInc('visitor_num',1);
					break;
				case -1:
					$this->model('ResourcesPage')->where($where)->setDec('visitor_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 页面信息
	 *
	 * @param $pageId 页面ID
	 *
	 * @reutrn array;
	 */
	public function getPageInfo($pageId,$field = '*'){
		
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('ResourcesPage')->field($field)->where($where)->find();
		
		return $pageData;
	}
	
	/**
	 *
	 * 删除页面
	 *
	 * @param $pageId 页面ID
	 *
	 * @reutrn int;
	 */
	public function removePageId($pageId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('ResourcesPage')->where($where)->find();
		if($pageData){
			
			$output = $this->model('ResourcesPage')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 页面修改
	 *
	 * @param $pageId 页面ID
	 * @param $pageNewData 页面数据
	 *
	 * @reutrn int;
	 */
	public function update($pageNewData,$pageId){
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('ResourcesPage')->where($where)->find();
		if($pageData){
			
			$pageNewData['lastupdate'] = $this->getTime();
			$this->model('ResourcesPage')->data($pageNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新页面
	 *
	 * @param $pageNewData 页面数据
	 *
	 * @reutrn int;
	 */
	public function insert($pageNewData){
		
		$pageNewData['subscriber_identity'] =$this->session('uid');
		$pageNewData['dateline'] = $this->getTime();
			
		$pageNewData['lastupdate'] = $pageNewData['dateline'];
		$this->model('ResourcesPage')->data($pageNewData)->add();
	}
}