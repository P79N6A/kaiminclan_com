<?php
/**
 *
 * 产品编辑
 *
 * 20180301
 *
 */
class ProductSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'productId'=>array('type'=>'digital','tooltip'=>'产品ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'类型','default'=>0),
			'income'=>array('type'=>'string','tooltip'=>'收益方式','length'=>120),
			'threshold'=>array('type'=>'money','tooltip'=>'门槛','length'=>120),
			'deadline'=>array('type'=>'digital','tooltip'=>'期限','length'=>120),
			'scale'=>array('type'=>'money','tooltip'=>'规模','length'=>120),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'币种','length'=>120),
			'profit'=>array('type'=>'money','tooltip'=>'预期收益','length'=>120),
			'company_identity'=>array('type'=>'digital','tooltip'=>'发行机构','length'=>120),
			'broker_identity'=>array('type'=>'digital','tooltip'=>'托管机构','length'=>120),
			'employee_identity'=>array('type'=>'digital','tooltip'=>'投资经理','length'=>120),
			'target'=>array('type'=>'string','tooltip'=>'目标','length'=>120),
			'proportion'=>array('type'=>'string','tooltip'=>'原则及比例','length'=>120),
			'principle'=>array('type'=>'string','tooltip'=>'收益及分配','length'=>120)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$productId = $this->argument('productId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'income' => $this->argument('income'),
			'threshold' => $this->argument('threshold'),
			'deadline' => $this->argument('deadline'),
			'scale' => $this->argument('scale'),
			'currency_identity' => $this->argument('currency_identity'),
			'profit' => $this->argument('profit'),
			'company_identity' => $this->argument('company_identity'),
			'broker_identity' => $this->argument('broker_identity'),
			'employee_identity' => $this->argument('employee_identity'),
			'target' => $this->argument('target'),
			'proportion' => $this->argument('proportion'),
			'principle' => $this->argument('principle')
		);
		
		if($productId){
			$this->service('FundProduct')->update($setarr,$productId);
		}else{
			
			if($this->service('FundProduct')->checkTitle($setarr['title'])){
				
				$this->info('产品已存在',4001);
			}
			
			$this->service('FundProduct')->insert($setarr);
		}
	}
}
?>