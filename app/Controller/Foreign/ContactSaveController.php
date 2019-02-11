<?php
/**
 *
 * 合约编辑
 *
 * 20180301
 *
 */
class ContactSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'contactId'=>array('type'=>'digital','tooltip'=>'合约ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'code'=>array('type'=>'letter','tooltip'=>'代码'),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'货币'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$contactId = $this->argument('contactId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'code' => $this->argument('code'),
			'currency_identity' => $this->argument('currency_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($contactId){
			$this->service('ForeignContact')->update($setarr,$contactId);
		}else{
			
			if($this->service('ForeignContact')->checkContactTitle($setarr['title'])){
				
				$this->info('合约已存在',4001);
			}
			
			$this->service('ForeignContact')->insert($setarr);
		}
	}
}
?>