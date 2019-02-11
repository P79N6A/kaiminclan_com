<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProjectSubjectService extends Service
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
	public function getSubjectList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProjectSubject')->where($where)->count();
		if($count){
			$handle = $this->model('ProjectSubject')->where($where);
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
	public function checkSubjectTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProjectSubject')->where($where)->count();
		}
		return 0;
	}

    /**
     *
     * 获取指定用户加入的项目
     *
     * @return int
     */
	public function getSubjectIdByUid($employeeId){

        $subjectIds = array();

	    $employeeId = intval($employeeId);

	    $where = array(
	        'employee_identity'=>$employeeId
        );
	    $list = $this->model('ProjectLeaguer')->field('subject_identity')->where($where)->select();
	    if($list){
	        foreach ($list as $key=>$data){
	            $subjectIds[] = $data['subject_identity'];
            }
        }
	    return $subjectIds;
    }
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $subjectId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSubjectInfo($subjectId,$field = '*'){
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('ProjectSubject')->field($field)->where($where)->select();
		
		return $subjectData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $subjectId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSubjectId($subjectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('ProjectSubject')->where($where)->find();
		if($subjectData){
			
			$output = $this->model('ProjectSubject')->where($where)->delete();
			
			$this->service('PaginationItem')->removeSubjectIdAllItem($subjectId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $subjectId 模块ID
	 * @param $subjectNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($subjectNewData,$subjectId){
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('ProjectSubject')->where($where)->find();
		if($subjectData){
			
			$subjectNewData['lastupdate'] = $this->getTime();
			$this->model('ProjectSubject')->data($subjectNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $subjectNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($subjectNewData){
		
		$subjectNewData['subscriber_identity'] =$this->session('uid');
		$subjectNewData['dateline'] = $this->getTime();
		$subjectNewData['sn'] = $this->get_sn();
			
		$subjectNewData['lastupdate'] = $subjectNewData['dateline'];
		$subjectId = $this->model('ProjectSubject')->data($subjectNewData)->add();
		if($subjectId){
		    $this->service('ProjectLeaguer')->pushLeaguer($subjectId);
        }
		return $subjectId;
	}
}