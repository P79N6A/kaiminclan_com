<?php
 
class QuotationTestConsole extends Console {
	
	
	public function fire(){		
		var_dump(date('Y-m-d H:i:s',-2147483648));
	    die();
		$this->service('PositionPurchase')->insert(array(
			'code' => '123',
			'id' => 1,
			'idtype' => 5,
			'happen_date' => '2018-12-31 12:25:35',
			'univalent' => 1,
			'quantity' => 1,
			'remark' => 'test'));
		
	}
	
}
