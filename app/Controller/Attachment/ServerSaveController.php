<?php
/**
 *
 * 服务器编辑
 *
 * 20180301
 *
 */
class serverSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'serverId'=>array('type'=>'digital','tooltip'=>'服务器ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'名称','length'=>60),
			'host'=>array('type'=>'string','tooltip'=>'地址'),
			'port'=>array('type'=>'digital','tooltip'=>'端口','default'=>80),
			'folder'=>array('type'=>'letter','tooltip'=>'目录','default'=>'./'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>ResourcesServerModel::RESOURCES_SERVER_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$serverId = $this->argument('serverId');
		
		$serverData = array(
			'title' => $this->argument('title'),
			'host' => $this->argument('host'),
			'port' => $this->argument('port'),
			'folder' => $this->argument('folder'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		if($serverId){
			$this->service('ResourcesServer')->update($serverData,$serverId);
		}else{
			
			if($this->service('ResourcesServer')->checkTitle($title)){
				
				$this->info('服务器已存在',4001);
			}
			
			$this->service('ResourcesServer')->insert($serverData);
		}
	}
}
?>