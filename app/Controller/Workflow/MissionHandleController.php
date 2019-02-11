<?php
/**
 * 任务处理
 * 工作流
 */
class MissionHandleController extends Controller {
	
	protected $permission = 'user';
	
	protected function setting(){
		return array(
			'missionId'=>array('type'=>'digital','tooltip'=>'任务ID'),
			'status'=>array('type'=>'digital','tooltip'=>'状态'),
			'opinion'=>array('type'=>'doc','tooltip'=>'备注'),
		);
	}
	public function fire(){
		
		$mission_identity = $this->argument('missionId');
		$status = $this->argument('status');
		$opinion = $this->argument('opinion');
		
		
		$where = array();
		$where['identity'] = $mission_identity;
		$missionData = $this->model('WorkflowMission')->where($where)->find();
		if(!$missionData){
			$this->info('任务不存在',4041);
		}
		if($this->session('roleId') != 5){
			
			if($missionData['liability_subscriber_identity'] != $this->session('uid')){
				$this->info('仅责任人允许执行此操作',4042);
			}
		}
		
		$setarr = array(
			'lastupdate'=>$this->getTime()
		);
		switch($status){
			case 1:
				//完成处理
				$setarr['stop_time'] = $setarr['lastupdate'];
				$setarr['status'] = WorkflowMissionModel::WORKFLOW_MISSION_STATUS_FINISH;
				$this->model('WorkflowMission')->data($setarr)->where($where)->save();
																
				$hybridData = array();
				$hybridData['id'] = $mission_identity;				
				$hybridData['rate'] = 0.01;
				$hybridData['idtype'] = 'mission';
				$hybridData['content'] = '完成->'.$opinion;
				$this->service('WorkflowHybrid')->insert($hybridData);				
				
				//统计成果
				$this->service('Quotation')->add('military','mission',1,array('employee_identity'=>$this->session('uid')));
				
				
				$where = array();
				$where['identity'] = $this->service('Account')->getRealTypeId($missionData['subscriber_identity'],'employee');
				$memberData = $this->model('ProjectMember')->where($where)->find();
				
				$href = 'http://'.__HOST__.'.'.__SITE_URL__.'/Faultiness/BulletinMission/missionId/'.$missionData['identity'];
		
				$content .= $memberData['fullname'].'：<br /><br /><br />';
		
				$content .= '以下任务需要你及时处理。请点击以下链接查看任务详情：<br /><br />';
				
				$content .= '(please click on the following link to view the details of the task:)<br /><br />';
				
				$content .= '<a href="'.$href.'" target="_blank">'.$href.'</a><br /><br />';
				
				$content .= '如果你的email程序不支持链接点击，请将上面的地址拷贝至你的浏览器(例如IE)的地址栏进入。<br /><br />';
			
				
				$content .= '(这是一封自动产生的email，请勿回复。)<br />';
				
				
				$toEmail = $memberData['email'];
				
				$result = $this->service('MessengerMessage')->sendEmail($toEmail,'任务确认['.$missionData['title'].']',$content,$param = array());
		
				break;
			case -1:
			
				//开始处理
				$setarr['start_time'] = $setarr['lastupdate'];
				$setarr['status'] = WorkflowMissionModel::WORKFLOW_MISSION_STATUS_ACCEPT;
				$this->model('WorkflowMission')->data($setarr)->where($where)->save();
				
								
				$hybridData = array();
				$hybridData['id'] = $mission_identity;				
				$hybridData['rate'] = 0.01;
				$hybridData['idtype'] = 'mission';
				$hybridData['content'] = $opinion;
				$this->service('WorkflowHybrid')->insert($hybridData);
				
				$this->model('WorkflowHybrid')->data($setarr)->add();
				
				
				break;
			case -2:
			case -3:
			case -4:
			case -5:
			case -6:
			case -7:
			case -8:
			case -9:
			case -10:
			case -11:
				//-2未完成
				$hybridData = array();
				$hybridData['id'] = $mission_identity;				
				$hybridData['rate'] = substr($status,1)/100;
				$hybridData['idtype'] = 'mission';
				$hybridData['content'] = $opinion;
				$this->service('WorkflowHybrid')->insert($hybridData);
				
				break;
		}
		
	}
}