<?php
/**
 *
 * 品种
 *
 * 价格
 *
 */
class QuotationSymbolModel extends Model
{
    protected $_name = 'quotation_symbol';
    protected $_primary = 'identity';
	
	protected $_database = 'quotation';
	//1外汇，2商品，3证券，4债券，5基金
	const QUOTATION_SYMBOL_IDTYPE_FOREIGN = 1;
	const QUOTATION_SYMBOL_IDTYPE_FUTURES = 2;
	const QUOTATION_SYMBOL_IDTYPE_STOCK = 3;
	const QUOTATION_SYMBOL_IDTYPE_BOND = 4;
	const QUOTATION_SYMBOL_IDTYPE_FUND = 5;
	
	/**
	 * 获取类型状态
	 *
	 * @return array
	 */
	public static function getIdtypeList(){
		return array(
			array('value'=>self::QUOTATION_SYMBOL_IDTYPE_FOREIGN,'label'=>'外汇'),
			array('value'=>self::QUOTATION_SYMBOL_IDTYPE_FUTURES,'label'=>'商品'),
			array('value'=>self::QUOTATION_SYMBOL_IDTYPE_STOCK,'label'=>'证券'),
			array('value'=>self::QUOTATION_SYMBOL_IDTYPE_BOND,'label'=>'债券'),
			array('value'=>self::QUOTATION_SYMBOL_IDTYPE_FUND,'label'=>'基金'),
		);
	}
	
	/**
	 * 获取类型状态名称
	 *
	 * @param $idtype 类型状态
	 *
	 * @return string
	 */
	public static function getIdtypeTitle($idtype){
		$idtypeTitle = '';
		$idtypeData = self::getIdtypeList();
		foreach($idtypeData as $key=>$data){
			if($data['value'] == $idtype){
				$idtypeTitle = $data['label'];
				break;
			}
		}
		
		return $idtypeTitle;
	}
}
