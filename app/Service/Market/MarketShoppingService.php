<?php
/**
 *
 * 购物车
 *
 * 销售
 *
 */
class  MarketShoppingService extends Service {
	
	
	
	/**
	 *
	 * 获取购物车列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getAllShoppingList($where = array(),$orderby = 'identity desc',$start = 0,$perpage = 0){
		
		
		$count = $this->model('MarketShopping')->where($where)->count();
		if($count){
			$shoppingHandle = $this->model('MarketShopping')->where($where)->orderby($orderby);
			if($start && $perpage){
				$shoppingHandle = $shoppingHandle->limit($start,$perpage,$count);
			}
			$listdata = $shoppingHandle->select();
			
			$idtypeList = array();
			foreach($listdata as $key=>$shopping){
				$idtypeList[$shopping['idtype']][] = $shopping['id'];
			}
			
			foreach($idtypeList as $idtype=>$ids){
				switch($idtype){
					case MarketShoppingModel::MARKET_SHOPPING_IDTYPE_GOOD:
							$goodsData = $this->service('Goods')->getGoodsInfobyIds($ids);
							if($goodsData){
								foreach($listdata as $key=>$shopping){
									if($shopping['idtype'] != MarketShoppingModel::MARKET_SHOPPING_IDTYPE_GOOD) continue;
									$listdata[$key]['good'] = $goodsData[$shopping['id']];
								}
							}
						break;
				}
			}
		}
		return array('count'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 查询订单ID下所有商品
	 *
	 * @return int $orderddId 订单ID;
	 *
	 * @return array 订单信息;
	 */
	public function getShoppingByOrderddIds($orderddId){
		
		$orderddShopping = array();
		
		$where = array(
			'orderdd_identity'=>$orderddId
		);	
		
		$listdata = $this->getAllShoppingList($where);
		if($listdata['count']){
			foreach($listdata['list'] as $key=>$shopping){
				$orderddShopping[$shopping['orderdd_identity']][] = $shopping;
			}
		}
		return $orderddShopping;
	}
	/**
	 *
	 * 检测订购是否存在
	 *
	 * @return int $id 数据ID;
	 * @return int $idtype 数据类型;
	 * @return int $protpery 数据属性;
	 *
	 * @return array 订单信息;
	 */
	public function checkShopping($id,$idtype,$protpery){
		
		$where = array();
		$where['code'] = $this->getShoppingCode($id,$idtype,$property);
		$where['status'] = array(MarketShoppingModel::MARKET_SHOPPING_STATUS_SHOPPING,MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT);
		$where['subscriber_identity'] = $this->session('uid');
		return $this->model('MarketShopping')->where($where)->count();
	}
	/**
	 *
	 * 检测订购是否提交
	 *
	 * @return int $id 数据ID;
	 *
	 * @return array 订单信息;
	 */
	public function checkShoppingCheckout($shoppingId = array()){
		
		$where = array();
		if($shoppingId){
			$where['identity'] = $shoppingId;
		}
		$where['status'] = array(MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT);
		$where['subscriber_identity'] = $this->session('uid');
		return $this->model('MarketShopping')->where($where)->count();
	}
	
	/**
	 *
	 * 获取订购商品信息
	 *
	 *
	 * @return int 匹配数;
	 */
	public function getShoppingCheckoutData(){
		
		$output = array();
		
		$where = array();
		$where['status'] = MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT;
		$where['subscriber_identity'] = $this->session('uid');
		
		$listdata = $this->model('MarketShopping')->where($where)->select();
		if($listdata){
			foreach($listdata as $key=>$shopping){
				$output[$shopping['business_identity']][] = $shopping;
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 获取订购商品信息
	 *
	 * @return int $id 数据ID;
	 *
	 * @return array 数据;
	 */
	public function getShoppingInfo($shoppingId){
		$output = array();
		
		
		$where = array();
		$where['identity'] = $shoppingId;
		$where['status'] = array(MarketShoppingModel::MARKET_SHOPPING_STATUS_SHOPPING,MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT);
		$where['subscriber_identity'] = $this->session('uid');
		
		$listdata =  $this->model('MarketShopping')->where($where)->select();
		if($listdata){
			
			foreach($listdata as $key=>$data){
				$output[$data['identity']] = $data;
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 获取订购商品详细信息
	 *
	 * @return int $id 数据ID;
	 *
	 * @return array 数据;
	 */
	public function getShoppingDetail($shoppingId){
		$output = array();
		
		
		$where = array();
		$where['identity'] = $shoppingId;
		
		$listdata = $this->getAllShoppingList($where);
		if($listdata['count'] > 0){
			if(!is_array($shoppingId)){
				$output = current($listdata['list']);
			}else{
				$output = $listdata['list'];
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 按照商品识别码提取信息
	 *
	 * @return string $code 识别码;
	 * @return int $orderddId 订单ID;
	 *
	 * @return array 数据;
	 */
	public function getShoppingByCodeInfo($code,$orderddId = 0){
		$output = array();
		
		
		$where = array();
		$where['code'] = $code;
		$where['subscriber_identity'] = intval($this->session('uid'));
		$where['orderdd_identity'] = $orderddId;
		
		return $this->model('MarketShopping')->where($where)->find();
	}
	
	/**
	 *
	 * 检测订购是否存在
	 *
	 * @return int $id 数据ID;
	 *
	 * @return int 匹配数;
	 */
	public function getShoppingIdCount($shoppingId){
		
		$where = array();
		$where['identity'] = $shoppingId;
		$where['status'] = array(MarketShoppingModel::MARKET_SHOPPING_STATUS_SHOPPING,MarketShoppingModel::MARKET_SHOPPING_STATUS_CHECKOUT);
		$where['subscriber_identity'] = $this->session('uid');
		
		return $this->model('MarketShopping')->where($where)->count();
	}
	/**
	 *
	 * 获取购物车识别码
	 *
	 * @return int $id 数据ID;
	 * @return int $idtype 数据类型;
	 * @return int $protpery 数据属性;
	 *
	 * @return int 订单号;
	 */
	public function getShoppingCode($id,$idtype,$protpery){		
		return md5($id.$idtype.$protpery);
	}
	
	/**
	 *
	 * 退货ID
	 *
	 * @param $shoppingId 订购ID
	 * @param $drawbackId 订购数据
	 *
	 * @reutrn int;
	 */
	public function setDrawbackId($drawbackId,$shoppingId){
		$this->update(array('shopping_identity'=>$drawbackId),$shoppingId);
	}
	
	/**
	 *
	 * 附件修改
	 *
	 * @param $shoppingId 订购ID
	 * @param $shoppingNewData 订购数据
	 *
	 * @reutrn int;
	 */
	public function update($shoppingNewData,$shoppingId){
		$where = array(
			'identity'=>$shoppingId
		);
		$shoppingData = $this->model('MarketShopping')->where($where)->find();
		if($shoppingData){
			
			$shoppingNewData['lastupdate'] = $this->getTime();
			$this->model('MarketShopping')->data($shoppingNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新订购
	 *
	 * @param $shoppingData 订购信息
	 *
	 * @reutrn int;
	 */
	public function insert($shoppingData){
		
		$shoppingData['subscriber_identity'] = $this->session('uid');
		$shoppingData['dateline'] = $this->getTime();
			
		$shoppingData['lastupdate'] = $shoppingData['dateline'];
		return $this->model('MarketShopping')->data($shoppingData)->add();
	}
	
	
	/**
	 *
	 * 调整订购数量
	 *
	 * @param $shoppingId 订购ID
	 * @param $quantity 数量
	 *
	 * @reutrn int;
	 */
	public function adjustShoppingQuantity($shoppingId,$quantity){
		
		$where = array();
		$where['identity'] = $shoppingId;
		$where['status'] = MarketShoppingModel::MARKET_SHOPPING_STATUS_SHOPPING;
		$where['subscriber_identity'] = $this->session('uid');
		$shoppingData = $this->model('MarketShopping')->where($where)->find();
		if($shoppingData){
			$quantity = $shoppingData['quantity']+$quantity;
			$shoppingData = array(
				'quantity' => $quantity,
				'amount' => $quantity*$shoppingData['univalent']
			);
			$this->update($shoppingData,$shoppingId);
		}
	}
	
	/**
	 *
	 * 删除订购
	 *
	 * @param $shoppingId 订购ID
	 *
	 * @reutrn int;
	 */
	public function remove($shoppingId){
		
		$where = array();
		$where['identity'] = $shoppingId;
		return $this->model('MarketShopping')->where($where)->delete();
	}
}