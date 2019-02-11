
<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class IntelligenceSubstanceService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getSubstanceList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('IntelligenceSubstance')->where($where)->count();
		if($count){
			$handle = $this->model('IntelligenceSubstance')->where($where);
			if($perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkSubstanceTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('IntelligenceSubstance')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $substanceId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSubstanceInfoByDocumentationId($documentation_identity,$page = 1){
		
		$where = array(
			'documentation_identity'=>$documentation_identity,
			'indexid'=>$page
		);
		
		$substanceData = $this->model('IntelligenceSubstance')->field('identity,title,content,dateline')->where($where)->find();
		
		return $substanceData;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $substanceId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSubstanceInfo($substanceId,$field = '*'){
		
		$where = array(
			'identity'=>$substanceId
		);
		
		$substanceData = $this->model('IntelligenceSubstance')->field($field)->where($where)->find();
		
		return $substanceData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $substanceId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSubstanceId($substanceId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$substanceId
		);
		
		$substanceData = $this->model('IntelligenceSubstance')->where($where)->find();
		if($substanceData){
			
			$output = $this->model('IntelligenceSubstance')->where($where)->delete();
			
			$this->service('PaginationItem')->removeSubstanceIdAllItem($substanceId);
		}
		
		return $output;
	}
	
	public function save($content,$documentationId,$documentationTitle){
		
		$substanceData = array();
		
		$content = explode('<hr />',$content);
		
		$newPageCount = count($content);
		$currentDataline = $this->getTime();
		
		$where = array();
		$where['documentation_identity'] = $documentationId;
		$listdata = $this->model('IntelligenceSubstance')->where($where)->order('indexid asc')->select();
		if($listdata){
			$oldPageCount = count($listdata);
			if($newPageCount > $oldPageCount){
				//页面增加
				$cnt = 0;
				foreach($listdata as $key=>$data){
					$substanceData['identity'][] = $data['identity'];
					$substanceData['sn'][] = $data['sn'];
					$substanceData['content'][] = $content[$cnt];
					$substanceData['documentation_identity'][] = $data['documentation_identity'];
					$substanceData['title'][] = $documentationTitle;
					$substanceData['indexid'][] = $data['indexid'];
					$substanceData['status'][] = $data['status'];
					$substanceData['subscriber_identity'][] = $data['subscriber_identity'];
					$substanceData['dateline'][] = $data['dateline'];
					$substanceData['lastupdate'][] = $currentDataline;
					unset($content[$cnt]);
					$cnt++;
					
				}
				
				for($i=$cnt;$i<$newPageCount;$i++){
					$substanceData['identity'][] = NULL;
					$substanceData['sn'][] = $this->get_sn();
					$substanceData['content'][] = $content[$i];
					$substanceData['documentation_identity'][] = $documentationId;
					$substanceData['title'][] = $documentationTitle;
					$substanceData['indexid'][] = $i+1;
					$substanceData['status'][] = 0;
					$substanceData['subscriber_identity'][] = $this->getUID();
					$substanceData['dateline'][] = $currentDataline;
					$substanceData['lastupdate'][] = $currentDataline;
				}
			}
			elseif($newPageCount < $oldPageCount){
				//页面减少
				$cnt = 0;
				$removeSubstanceIds = array();
				foreach($listdata as $key=>$data){
					if($data['indexid'] > $newPageCount){
						$cnt ++;
						$removeSubstanceIds[] = $data['identity'];
						continue;
					}
					$substanceData['identity'][] = $data['identity'];
					$substanceData['sn'][] = $data['sn'];
					$substanceData['content'][] = $content[$cnt];
					$substanceData['documentation_identity'][] = $data['documentation_identity'];
					$substanceData['title'][] = $documentationTitle;
					$substanceData['indexid'][] = $data['indexid'];
					$substanceData['status'][] = $data['status'];
					$substanceData['subscriber_identity'][] = $data['subscriber_identity'];
					$substanceData['dateline'][] = $data['dateline'];
					$substanceData['lastupdate'][] = $currentDataline;
					unset($content[$cnt]);
					$cnt ++;
				}
				
				if($removeSubstanceIds){
					$where = array();
					$where['identity'] = $removeSubstanceIds;
					$this->model('IntelligenceSubstance')->where($where)->delete();
				}
				
			}else{
				//页面相等
				$cnt = 0;
				foreach($listdata as $key=>$data){
					$substanceData['identity'][] = $data['identity'];
					$substanceData['sn'][] = $data['sn'];
					$substanceData['content'][] = $content[$cnt];
					$substanceData['documentation_identity'][] = $data['documentation_identity'];
					$substanceData['title'][] = $documentationTitle;
					$substanceData['indexid'][] = $data['indexid'];
					$substanceData['status'][] = $data['status'];
					$substanceData['subscriber_identity'][] = $data['subscriber_identity'];
					$substanceData['dateline'][] = $data['dateline'];
					$substanceData['lastupdate'][] = $currentDataline;
					unset($content[$cnt]);
					$cnt++;
				}
			}
		}else{
				
			for($i=0;$i<$newPageCount;$i++){
				$substanceData['identity'][] = 0;
				$substanceData['sn'][] = $this->get_sn();
				$substanceData['content'][] = $content[$i];
				$substanceData['documentation_identity'][] = $documentationId;
				$substanceData['title'][] = $documentationTitle;
				$substanceData['indexid'][] = $i+1;
				$substanceData['status'][] = 0;
				$substanceData['subscriber_identity'][] = $this->getUID();
				$substanceData['dateline'][] = $currentDataline;
				$substanceData['lastupdate'][] = $currentDataline;
			}
		}
		
		$this->model('IntelligenceSubstance')->data($substanceData)->addMulti();
		
		return $newPageCount;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $substanceId 模块ID
	 * @param $substanceNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($substanceNewData,$substanceId){
		$where = array(
			'identity'=>$substanceId
		);
		
		$substanceData = $this->model('IntelligenceSubstance')->where($where)->find();
		if($substanceData){
			
			$substanceNewData['lastupdate'] = $this->getTime();
			$this->model('IntelligenceSubstance')->data($substanceNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $substanceNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($substanceNewData){
		
		$substanceNewData['sn'] = $this->get_sn();
		$substanceNewData['subscriber_identity'] =$this->session('uid');
		$substanceNewData['dateline'] = $this->getTime();
			
		$substanceNewData['lastupdate'] = $substanceNewData['dateline'];
		$this->model('IntelligenceSubstance')->data($substanceNewData)->add();
	}
}