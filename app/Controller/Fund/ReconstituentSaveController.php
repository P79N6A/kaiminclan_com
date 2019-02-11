<?php
/**
 *
 * 成份编辑
 *
 * 20180301
 *
 */
class ReconstituentSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'reconstituentId'=>array('type'=>'digital','tooltip'=>'成份ID','default'=>0),
			'product_identity'=>array('type'=>'digital','tooltip'=>'类型'),
			'id'=>array('type'=>'digital','tooltip'=>'产品成份ID'),
			'idtype'=>array('type'=>'digital','tooltip'=>'产品成份类型'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>'')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$reconstituentId = $this->argument('reconstituentId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'product_identity' => $this->argument('product_identity'),
			'id' => $this->argument('id'),
			'idtype' => $this->argument('idtype'),
			'remark' => $this->argument('remark')
		);
		
		if($reconstituentId){
			$this->service('FundReconstituent')->update($setarr,$reconstituentId);
		}else{
			
			if($this->service('FundReconstituent')->checkSymbolExists($setarr['id'],$setarr['idtype'])){
				
				$this->info('成份已存在',4001);
			}
			
			$this->service('FundReconstituent')->insert($setarr);
		}
	}
}
?>