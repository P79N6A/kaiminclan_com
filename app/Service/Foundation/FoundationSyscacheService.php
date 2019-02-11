<?php
/**
 *
 * 缓存
 *
 * 基础
 *
 */
class  FoundationSyscacheService extends Service {
	

	
	/**
	 *
	 * 获取缓存列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 缓存列表;
	 */
	public function getAllIndicentList($where = array(),$orderby = 'identity desc',$start = 1,$perpage = 10){
		$_where = array(
		);
		$where = array_merge($where,$_where);
		
		$count = $this->model('FoundationSyscache')->where($where)->count();
		if($count){
			$listdata = $this->model('FoundationSyscache')->field('cname,dateline,lastupdate')->where($where)->orderby($orderby)->limit($start,$perpage,$count)->select();
			
		}
		
		return $listdata;
	}
	
	/**
	 *
	 * 缓存信息
	 *
	 * @param $sysCacheKey 缓存KEY
	 *
	 * @reutrn array;
	 */
	public function getSyscahce($sysCacheKey){
		
		$syscacheData = array();
		
		$where = array(
			'cname'=>$sysCacheKey
		);
		
		$syscacheList = $this->model('FoundationSyscache')->where($where)->select();
		
		if(!is_array($sysCacheKey)){
			$syscacheData = current($syscacheList);
		}else{
			foreach($syscacheList as $key=>$data){
				$syscacheData[$data['cname']] = $data;
				
			}
		}
		
		return $syscacheData;
	}
	
	/**
	 *
	 * 删除缓存
	 *
	 * @param $cname 键
	 *
	 * @reutrn int;
	 */
	public function removeCacheByCname($cname){
		
		$output = 0;
		
		$where = array();
		$where['cname'] = $cname;
		$count = $this->model('FoundationSyscache')->where($where)->count();
		
		if($count){
			$output = $this->model('FoundationSyscache')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 缓存
	 *
	 * @param $cname 键
	 * @param $cdata 值
	 *
	 * @reutrn int;
	 */
	public function saveSyscache($cname,$cdata){
		if(!$cname){
			return array();
		}
		
		if(is_array($cdata)){
			$cdata = json_encode($cdata);
		}
		
		
		$syscacheNewData = array(
			'cdata'=>$cdata
		);
		
		$where = array();
		$where['cname'] = $cname;
		$count = $this->model('FoundationSyscache')->where($where)->count();
		if($count){
			$syscacheNewData['lastupdate'] = $this->getTime();
			$this->model('FoundationSyscache')->data($syscacheNewData)->where($where)->save();
		}else{
			$syscacheNewData['cname'] = $cname;
			$syscacheNewData['dateline'] = $this->getTime();
				
			$syscacheNewData['lastupdate'] = $syscacheNewData['dateline'];
			$syscacheId = $this->model('FoundationSyscache')->data($syscacheNewData)->add();
		}
		
		
	}
}