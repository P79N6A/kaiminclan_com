<?php
/**
 *
 * 调账编辑
 *
 * 20180301
 *
 */
class DividendSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'dividendId'=>array('type'=>'digital','tooltip'=>'调账ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'情况说明'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$dividendId = $this->argument('dividendId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'amount' => $this->argument('amount'),
			'remark' => $this->argument('remark')
		);
		
		if($dividendId){
			$this->service('SecuritiesDividend')->update($setarr,$dividendId);
		}else{
			
			if($this->service('SecuritiesDividend')->checkDividendTitle($setarr['title'])){
				
				$this->info('调账已存在',4001);
			}
			
			$this->service('SecuritiesDividend')->insert($setarr);
		}
	}
}
?>