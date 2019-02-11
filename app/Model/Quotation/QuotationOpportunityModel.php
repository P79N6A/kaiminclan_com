<?php
/**
 *
 * 机会
 *
 * 统计分析
 *
 */
class QuotationOpportunityModel extends Model
{
    protected $_name = 'quotation_opportunity';
    protected $_primary = 'identity';
	
	protected $_database = 'analysis';
	
	//开多仓
	const QUOTATION_OPPORTUNITY_STYLE_OPEN_BUY = 1;
	//平多仓
	const QUOTATION_OPPORTUNITY_STYLE_CLOSE_BUY = 2;
	//开空仓
	const QUOTATION_OPPORTUNITY_STYLE_OPEN_SELL = 3;
	//平空仓
	const QUOTATION_OPPORTUNITY_STYLE_CLOSE_SELL = 4;
	
	/**
	 * 获取机会状态
	 *
	 * @return array
	 */
	public static function getStyleList(){
		return array(
			array('value'=>self::QUOTATION_OPPORTUNITY_STYLE_OPEN_BUY,'label'=>'开多仓'),
			array('value'=>self::QUOTATION_OPPORTUNITY_STYLE_CLOSE_BUY,'label'=>'平多仓'),
			array('value'=>self::QUOTATION_OPPORTUNITY_STYLE_OPEN_SELL,'label'=>'开空仓'),
			array('value'=>self::QUOTATION_OPPORTUNITY_STYLE_CLOSE_SELL,'label'=>'平空仓'),
		);
	}
	
	/**
	 * 获取机会状态
	 *
	 * @param $style 角色状态
	 *
	 * @return string
	 */
	public static function getStyleTitle($style){
		$styleTitle = '';
		$styleData = self::getStyleList();
		foreach($styleData as $key=>$data){
			if($data['value'] == $style){
				$styleTitle = $data['label'];
				break;
			}
		}
		
		return $styleTitle;
	}
}
