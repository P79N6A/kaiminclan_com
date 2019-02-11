<?php
class TemplateThemeBlock extends Block {
	public function getdata($param){
		
		$start = 1;
		$perpage = 10;
		$order = 'identity desc';
		$where = array();
		
		$listdata = $this->service('TemplateTheme')->getThemeList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}