<?php

//信用卡
$amount = 24211;
$sourceAmount = $amount;
$monthAmount = 0;
$interest = 0.0005;
$curAccrual = 0;
//还款比例
$point = 0.05;
$point = 0.1;


$deadline = 24;

$period = 31;

$interestTotal = 0;

for($cnt=1;$cnt <= $deadline; $cnt++){
	if($cnt > 1){
		$curAccrual = $amount*$interest*$period;
		$interestTotal += $curAccrual;
	}
	echo $amount*$point+$curAccrual.'>>'.'本金：'.$amount.'利息：'.$curAccrual.'<br />';
	$amount = round($amount-$amount*$point,0);
}

echo '总额：'.$interestTotal.'年利率：'.round($interestTotal/$sourceAmount/($deadline/12),2);


die();

//等额本金

//总额
$amount = 30000;
//月
$deedline = 12;

$monthAmount = round($amount/$deedline,2);
//月利率
$interest = 0.0005*365/12;


//已还金额
$descriptAmount = 0;

$totalAmount = 0;
//等额本金
for($i=1; $i<=$deedline;$i++){
	
	$interestAmount = round(($amount-$descriptAmount)*$interest,2);
	$curAmount = ($monthAmount+$interestAmount);
	
	echo '本金：'.$monthAmount;
	echo '>>利息：'.$interestAmount;
	echo '>>还款总额：'.round($curAmount,2).'<hr />';
	$descriptAmount += $monthAmount;
	
	$totalAmount+= $monthAmount+$interestAmount;
}
echo $totalAmount/$deedline;
/*
echo $interest;
echo '<hr />';
//等额本息
for($i=1; $i<=$deedline;$i++){
	$a = round(($amount*$interest*(1+$interest)^$deedline)/((1+$interest)^$deedline-1),4);
	echo $monthAmount+$a;
	echo '<hr />';
}

*/
die();

//等额本息
/*

1：每月月供额＝(贷款本金÷还款月数)＋(贷款本金－已归还本金累计额)×月利率
2：每月应归还本金＝贷款本金÷还款月数
3：月利率＝日利率×30

*/

$amount = 20000;

$interest = 0.00045;
$interest = 0.000425;


$deadline = 60;
$aAmount = $curAmount = 0;
//月利率
$mInterest = round($interest*365/12,3);
//本月余额
$curMonthAmount = $amount;

$monthAmount = round($amount/$deadline,2);

$interestTotal = $amount*$interest;
echo '月利率：'.$mInterest.'<hr />';

$deposit = 0;
$interestAmount = 0;
for($i=1;$i<=$deadline;$i++){
	echo '期数：'.$i.'<Br />';
	$in = ($amount-$deposit)*$mInterest;
	echo '固定：'.$monthAmount.'本金'.($amount-$deposit).'>>利率'.$mInterest.'利息'.$in.'合计还款：'.($monthAmount+$in);
	echo '<hr />';
	$deposit += $monthAmount;
	$interestAmount += $in;
}

echo '利息总额：'.$interestAmount.'年利率：'.$interestTotal/$amount;


function to_base($value,$b=62){
	$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$r = $value%$b;
	$result = substr($base,$r,1);
	$q = floor($value/$b);
	while($q){
		$r = $q%$b;
		$q = floor($q/$b);
		$result = substr($base,$r,1).$result;
	}
	
	return $result;
}
?>
<script type="text/javascript">
alert("test");
var url = "http://www.test.bamboo.com/interest.php";
alert(url.replace(/http/, "https"));
</script>