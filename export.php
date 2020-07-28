<?php

/*include DB file*/
include('db.php');
include('upload.inc.php');
$check_restore_status = check_restore();

function export_data(){
	global $con;
	$dumped_table = 'record_transactions_dumped';
	
	$ids = select_row($con, "SELECT cust_trans_id FROM `record_transactions_dumped` WHERE id > (SELECT id FROM record_transactions_dumped WHERE cust_trans_id='RESTORED')");
	$f_ids = array();
	foreach($ids as $id){
		$f_ids[] = "'".$id['cust_trans_id']."'";
	}
	
	$f_ids = implode(',', $f_ids);
	$res = select_row($con, "select * from $dumped_table where customer_id IS NOT NULL AND cust_trans_id IN (".$f_ids.")");
	/* print_r($res); die; */
	if($res){
		header("Content-Type:application/csv");
		header("Content-Disposition:attachment;filename=restored_transactions_".time().".csv");
		$fp = fopen('php://output', 'w');
		$is_header = true;
		foreach($res as $r){
			if($is_header){
				fputcsv($fp, array_keys($r), '|');
				$is_header = false;
			}
			fputcsv($fp, array_values($r), '|');
		}
		fclose($fp);
		exit();
	}
}

if($check_restore_status){
	export_data();
}else{ ?>
	<p style="color:red"><b>Please restore suspicious transactions if any and try again.</b></p>
<?php }

?>