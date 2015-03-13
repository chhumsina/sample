<div>Dear All,</div> 
<br/>
<div>Please see the list of dealers with condition below:</div>
<br/>

<?php
if ($dealers != null) {
?>
<h3>1. Dealer's balance game is less than or equal 1,000 KHR at {{$toDate}}</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
foreach ($dealers as $key => $dealer) {
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
</table>
<?php
}
?>



<?php
if ($dealers2 != null) {
?>
<br/>

<h3>2. Dealer's balance game is greater than 1,000 KHR and less than or equal 100,000 KHR at {{$toDate}}</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
foreach ($dealers2 as $key => $dealer) {
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
</table>
<?php
}
?>



<?php
if ($dealers3 != null) {
?>
<br/>

<h3>3. Dealer's amount transaction game from {{$fromDate}} to {{$toDate}} is less than or equal 10,000 KHR.</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>Sale Amount</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
foreach ($dealers3 as $key => $dealer) {
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_request_amount,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
</table>
<?php
}
?>



<?php
if ($dealers4 != null) {
?>
<br/>

<h3>4. Dealer no transaction sale game from {{$fromDate}} to {{$toDate}}.</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
foreach ($dealers4 as $key => $dealer) {
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
</table>
<?php
}
?>

<?php
if ($dealers5 != null) {
	$day = date("d");
?>
<br/>

<h3>5. Dealer top 10 game from {{$fromDate}} to {{$toDate}}.</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>Sale Amount</th>
		<th>Sale Average of {{intval($day)}}day</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
foreach ($dealers5 as $key => $dealer) {
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	//$r.= '<td align="right">'.$dealer->wallet.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_amount.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_amount,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_amount/intval($day).'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_amount/intval($day),2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
</table>
<?php
}
?>

<?php
if ($dealers6 != null) {
?>
<br/>

<h3>6. Dealer Deposit game from {{$yesterdayFrom}} to {{$yesterdayTo}}.</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>Deposit Amount</th>
		<th>Deposit Commision</th>
		<th>Deposit Balance</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
$sumAmount = 0;
$sumComm = 0;
$sumBalance = 0;
foreach ($dealers6 as $key => $dealer) {
	$sumAmount += $dealer->sum_amt;
	$sumComm += $dealer->sum_comm;
	$sumBalance += $dealer->sum_amt_deposit_balance;
	$wallet_currency__id = $dealer->wallet_currency__id;
	
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	//$r.= '<td align="right">'.$dealer->wallet.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_amt.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_amt,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_comm.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_comm,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_amt_deposit_balance.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_amt_deposit_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
	<tr>
		<th></th>
		<th></th>
		<th style="width: 200"></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">Total: <?php echo number_format($sumAmount,2).' '.$wallet_currency__id; ?></th>
		<th align="right">Total: <?php echo number_format($sumComm,2).' '.$wallet_currency__id; ?></th>
		<th align="right">Total: <?php echo number_format($sumBalance,2).' '.$wallet_currency__id; ?></th>
		<th></th>
		<th></th>
	</tr>
</table>
<?php
}
?>



<?php
if ($dealers7 != null) {
?>
<br/>

<h3>7. Dealer Credit game from {{$yesterdayFrom}} to {{$yesterdayTo}}.</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>Credit Amount</th>
		<th>Credit Commision</th>
		<th>Credit Balance</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
$sum = 0;

$sumAmount = 0;
$sumComm = 0;
$sumBalance = 0;
foreach ($dealers7 as $key => $dealer) {
	$sum = 0;
	$sum = (double)$dealer->sum_amt+(double)$dealer->sum_comm;
	
	$sumAmount += $dealer->sum_amt;
	$sumComm += $dealer->sum_comm;
	$sumBalance += $sum;
	$wallet_currency__id = $dealer->wallet_currency__id;

	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	//$r.= '<td align="right">'.$dealer->post_balance.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_amt.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_amt,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_comm.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_comm,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$sum.'</td>';
	$r.= '<td align="right">'.number_format($sum,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
	<tr>
		<th></th>
		<th></th>
		<th style="width: 200"></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">Total: <?php echo number_format($sumAmount,2).' '.$wallet_currency__id; ?></th>
		<th align="right">Total: <?php echo number_format($sumComm,2).' '.$wallet_currency__id; ?></th>
		<th align="right">Total: <?php echo number_format($sumBalance,2).' '.$wallet_currency__id; ?></th>
		<th></th>
		<th></th>
	</tr>
</table>
<?php
}
?>


<?php
if ($dealers8 != null) {
?>
<br/>

<h3>8. Dealer Payback game from {{$yesterdayFrom}} to {{$yesterdayTo}}.</h3>
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Dealer ID</th>
		<th style="width: 200">Dealer Name</th>
		<th>Dealer Contact Number</th>
		<th>Province</th>
		<th>District</th>
		<th>Dealer Wallet Game</th>
		<th>Payback Amount</th>
		<th>TID</th>
		<th>Sale Staff Responsible</th>
	</tr>
<?php
$i = 0;
$sumAmount = 0;

foreach ($dealers8 as $key => $dealer) {
	$sumAmount += $dealer->sum_amt;
	$wallet_currency__id = $dealer->wallet_currency__id;
	$i++;
	$r = '<tr>';
	$r.= '<td>'.$i.'</td>';
	$r.= '<td>'.$dealer->id.'</td>';
	$r.= '<td>'.$dealer->name.'</td>';
	$r.= '<td>'.$dealer->phone.'</td>';
	$r.= '<td>'.$dealer->province.'</td>';
	$r.= '<td>'.$dealer->district.'</td>';
	//$r.= '<td align="right">'.$dealer->post_balance.'</td>';
	$r.= '<td align="right">'.number_format($dealer->post_balance,2).' '.$dealer->wallet_currency__id.'</td>';
	//$r.= '<td align="right">'.$dealer->sum_amt.'</td>';
	$r.= '<td align="right">'.number_format($dealer->sum_amt,2).' '.$dealer->wallet_currency__id.'</td>';
	$r.= '<td>'.$dealer->serial.'</td>';
	$r.= '<td>';
	
	$sales = DB::table('sales')->where('hold_dealers','lIKE','%'.$dealer->id.'%')->get();
	if ($sales!=null) {
		foreach ($sales as $key => $sale) {
			$r.= '<div>';
			$r.= $sale->name.'(Tel:'.$sale->phone.')';
			$r.= '</div>';
		}
	}
	$r.= '</td>';
	$r.= '</tr>';
	echo $r;
}
?>
	<tr>
		<th></th>
		<th></th>
		<th style="width: 200"></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">Total: <?php echo number_format($sumAmount,2).' '.$wallet_currency__id; ?></th>
		<th></th>
		<th></th>
	</tr>
</table>
<?php
}
?>