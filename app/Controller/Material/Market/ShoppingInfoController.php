<?php
/**
 *
 * 购物车信息
 *
 * 营销
 *
 */
class ShoppingInfoController extends Controller {
	
	/** 权限 */
	protected $permission = 'user';
	/** 访问方式 */
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'start'=>array('type'=>'digital','tooltip'=>'订购类型','default'=>1),
			'perpage'=>array('type'=>'digital','tooltip'=>'订购类型','default'=>10),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$start = $this->argument('start');
		$perpage = $this->argument('perpage');
		
		
		$where = array();
		$where['subscriber_identity'] = $this->session('uid');
		$where['status'] = MarketShoppingModel::MARKET_SHOPPING_STATUS_SHOPPING;
		
		$listdata = $this->service('MarketShopping')->getAllShoppingList($where,'identity desc',$start,$perpage);
		
		
		$this->assign('list',$listdata['list']);
		$this->assign('total',$listdata['count']);
		$this->assign('start',$start);
		$this->assign('perpage',$perpage);
	}
}
?>