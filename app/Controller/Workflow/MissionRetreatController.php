<?php
/**
 * 任务退回
 *
 * 工作流
 */
class MissionRetreatController extends Controller {
	
	protected $permission = 'user';
	
	protected function setting(){
		return array(
			'missionId'=>array('type'=>'digital','tooltip'=>'任务ID'),
			'opinion'=>array('type'=>'doc','tooltip'=>'意见'),
			'liability_subscriber_identity'=>array('type'=>'digital','tooltip'=>'责任人'),
		);
	}
	public function fire(){
		
		$mission_identity = $this->argument('missionId');
		$opinion = $this->argument('opinion');
		$liability_subscriber_identity = $this->argument('liability_subscriber_identity');
				
		$where = array();
		$where['identity'] = $mission_identity;
		$missionData = $this->model('WorkflowMission')->where($where)->find();
		if(!$missionData){
			$this->info('任务不存在',4041);
		}
		
		
		$roleId = $this->session('roleId');
		if($roleId != 5 && $missionData['liability_subscriber_identity'] != $this->session('uid')){
			$this->info('项目负责人，或责任人方可执行此操作',4001);
		}
		
		if($liability_subscriber_identity == $this->session('uid')){
			$this->info('不能退回给自己',4001);
		}
		
			
		$where = array();
		$where['identity'] = $mission_identity;
	
		$setarr = array(
			'lastupdate'=>$this->getTime(),
			'opinion'=>$opinion,
			'status'=>WorkflowMissionModel::WORKFLOW_MISSION_STATUS_RETREAT,
			'liability_subscriber_identity'=>$liability_subscriber_identity
		);
		$this->model('WorkflowMission')->data($setarr)->where($where)->save();
		
			
			
		$href = 'http://'.__HOST__.'.'.__SITE_URL__.'/Workflow/MissionHandle/missionId/'.$missionData['identity'];
		
		$content .= $memberData['fullname'].'：<br /><br /><br />';

		$content .= '以下任务需要你及时处理。请点击以下链接查看任务详情：<br /><br />';
		
		$content .= '(please click on the following link to view the details of the task:)<br /><br />';
		
		$content .= '<a href="'.$href.'" target="_blank">'.$href.'</a><br /><br />';
		
		$content .= '如果你的email程序不支持链接点击，请将上面的地址拷贝至你的浏览器(例如IE)的地址栏进入。<br /><br />';
		
		
		$content .= '(这是一封自动产生的email，请勿回复。)';
		
		
		$toEmail = $memberData['email'];
		
		$result = $this->service('MessengerMessage')->sendEmail($toEmail,'任务处理['.$missionData['title'].']',$content);
		
	}
}