<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProjectLeaguerService extends Service
{
    public function getAllowedSubjectIds(){
        return 0;
    }
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getLeaguerList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProjectLeaguer')->where($where)->count();
		if($count){
			$handle = $this->model('ProjectLeaguer')->where($where);
			if($perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$positionIds = $employeeIds = $subjectIds = array();
			foreach ($listdata as $key=>$data){
			    $subjectIds[] = $data['subject_identity'];
			    $employeeIds[] = $data['employee_identity'];
                $positionIds[] = $data['position_identity'];
            }

			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);
            $employeeData = $this->service('OrganizationEmployee')->getEmployeeInfo($employeeIds);
            $positionData = $this->service('OrganizationPosition')->getPositionData($positionIds);
            foreach($listdata as $key=>$data){
                $listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
                $listdata[$key]['employee'] = isset($employeeData[$data['employee_identity']])?$employeeData[$data['employee_identity']]:array();
                $listdata[$key]['position'] = isset($positionData[$data['position_identity']])?$positionData[$data['position_identity']]:array();
            }
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
	public function checkLeaguerTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProjectLeaguer')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $leaguerId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getLeaguerInfo($leaguerId,$field = '*'){
		
		$where = array(
			'identity'=>$leaguerId
		);
		
		$leaguerData = $this->model('ProjectLeaguer')->field($field)->where($where)->find();
		
		return $leaguerData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $leaguerId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeLeaguerId($leaguerId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$leaguerId
		);
		
		$leaguerData = $this->model('ProjectLeaguer')->where($where)->find();
		if($leaguerData){
			
			$output = $this->model('ProjectLeaguer')->where($where)->delete();
			
			$this->service('PaginationItem')->removeLeaguerIdAllItem($leaguerId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $leaguerId 模块ID
	 * @param $leaguerNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($leaguerNewData,$leaguerId){
		$where = array(
			'identity'=>$leaguerId
		);
		
		$leaguerData = $this->model('ProjectLeaguer')->where($where)->find();
		if($leaguerData){
			
			$leaguerNewData['lastupdate'] = $this->getTime();
			$this->model('ProjectLeaguer')->data($leaguerNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $leaguerNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($leaguerNewData){
		
		$leaguerNewData['subscriber_identity'] =$this->session('uid');
		$leaguerNewData['dateline'] = $this->getTime();
			
		$leaguerNewData['lastupdate'] = $leaguerNewData['dateline'];
		$this->model('ProjectLeaguer')->data($leaguerNewData)->add();
	}

    /**
     *
     * 推送默认成员
     * @param $subjectId
     */
	public function pushLeaguer($subjectId){
        $leaguerData = array(
            'subject_identity'=>$subjectId,
            'employee_identity'=>$this->session('employee_identity'),
            'position_identity'=>0,
        );
        $this->insert($leaguerData);
    }
}