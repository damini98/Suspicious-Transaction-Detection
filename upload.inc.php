<?php

/*include DB file*/
include('db.php');
ob_start();	

if(function_exists('date_default_timezone_set')){
	date_default_timezone_set('Asia/Kolkata');
}

function upload_data(){
	$filePath = 'temp_upload.csv';
	$uploadTable = 'record_transactions';
	global $con;	
	
	/*move uploaded file*/
	if(isset($_FILES['uploadFile']['tmp_name'])){
		if(move_uploaded_file($_FILES['uploadFile']['tmp_name'], $filePath)){
			
			/*clean file content*/
			$string = @file_get_contents($filePath);
			$string = preg_replace('~\r\n?~', "\n", $string);
			file_put_contents($filePath, $string);
			
			/*empty table*/
			mysqli_query($con, "TRUNCATE TABLE ".$uploadTable);
			
			/*insert all file data*/
			$query = sprintf("LOAD DATA LOCAL INFILE '%s'
				REPLACE
				INTO TABLE %s
				FIELDS TERMINATED BY ','
				ENCLOSED BY '\"' 
				ESCAPED BY '\\\' 
				LINES TERMINATED BY '\\n'
				IGNORE 1 LINES 
				(`cust_trans_id`, `customer_id`, `type_of_operation`, `amount`, `salary_or_income`, `is_loan_defaulter`, `tans_date_time`)
				", $filePath, $uploadTable);
				
			if(mysqli_query($con, $query)){
				unlink($filePath);
				echo "<script>location.href='cassandra_deletion_dynamic.php?action=upload&msg=success';</script>";
			}else{
				die("Error inserting data in table");
			}			
			
		}else{
			die("Can not upload file");
		}
	}else{
		die("Uploaded File not found");
	}
}

function upload_dumped_data(){
	$filePath = 'temp_upload_dumped.json';
	$uploadTable = 'record_transactions_dumped';
	
	global $con;

	function is_valid_json($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	 
	/*move uploaded file*/
	if(isset($_FILES['uploadDumped']['tmp_name'])){
		if(move_uploaded_file($_FILES['uploadDumped']['tmp_name'], $filePath)){
			
			/*check JSON data*/
			$string = @file_get_contents($filePath);
			if(is_valid_json($string)){
				$jsonArray = json_decode($string);
				//print_r($jsonArray);
				$part_key = $part_pos = $row_type = $row_pos = $cell_name = $cell_val = $cell_tstamp = $liveness = '';
				$empty_table = $err = false;
				foreach($jsonArray as $json){
					$part_key = $json->partition->key[0];
					$part_pos = $json->partition->position;
					
					$query = "insert into $uploadTable set cust_trans_id='$part_key'";
					if(isset($json->partition->deletion_info)){
						$mtime = Date('Y-m-d H:i:s', strtotime($json->partition->deletion_info->marked_deleted));
						$dtime = Date('Y-m-d H:i:s', strtotime($json->partition->deletion_info->local_delete_time));
						$query .= ", marked_deleted='".$mtime."', local_deleted_time='".$dtime."'";
						/*empty table*/
						if(!$empty_table){
							$empty_table = true;
							mysqli_query($con, "TRUNCATE TABLE ".$uploadTable);
						}
						if(mysqli_query($con, $query)){
							$err = true;
						}
					}else{
						
						foreach($json->rows as $row){
							$row_type = $row->type;
							$row_pos = $row->position;
							$liveness = '';
							if(isset($row->liveness_info)){
								$liveness = Date('Y-m-d H:i:s', strtotime($row->liveness_info->tstamp));
							}
							
							$tstamp = false;
							foreach($row->cells as $cell){
								$cell_name = $cell->name;
								$cell_val = $cell->value;
								if(is_bool($cell->value)){
									if($cell->value == false){
										$cell_val = 'False';
									}else{
										$cell_val = 'True';
									}
								}
								$cell_tstamp = '';
								if(isset($cell->tstamp) && !$tstamp){
									$tstamp = true;
									$cell_tstamp = Date('Y-m-d H:i:s', strtotime($cell->tstamp));
									$query .= ", tans_date_time='$cell_tstamp'";
								}
								//print_r($cell);
								//echo "\n $part_key \t $part_pos \t $row_type \t $row_pos \t $liveness \t $cell_name \t $cell_val \t $cell_tstamp \n";
								
								$query .= ", $cell_name='$cell_val'";
							}
							/*empty table*/
							if(!$empty_table){
								$empty_table = true;
								mysqli_query($con, "TRUNCATE TABLE ".$uploadTable);
							}
							if(mysqli_query($con, $query)){
								$err = true;
							}
						}
					}
				}
							
				if($err){
					unlink($filePath);
                                        echo "<script>location.href='cassandra_deletion_dynamic.php?action=dump&msg=success';</script>";
				}else{
					die("Error inserting data in table");
				}
				
			}else{
				die('File contains invalid JSON data');
			}
			
		}else{
			die("Can not upload file");
		}
	}else{
		die("Uploaded File not found");
	}
}

function select_row($db, $sql){
	if ($sql!=""){
		$data = array();
		if ($result = @mysqli_query($db, $sql)){
			while($row = @mysqli_fetch_assoc($result)){
				$data[] = $row;
			}
			return $data;
		}
	}
}
	
function find_suspacious_data(){
	global $con;
	$org_table = 'record_transactions';
	$dumped_table = 'record_transactions_dumped';
	$result = array('records' => array(), 'count'=>0);
	
	/* if(check_restore() != true){ */
		/*suspacious update - defaulter changed*/
		$s_defaulter = 'SELECT a.*, "suspacious_update", "defaulter update" AS updated_col, b.is_loan_defaulter AS updated_value FROM `record_transactions` a, `record_transactions_dumped` b WHERE a.is_loan_defaulter = "True" AND b.is_loan_defaulter="False" AND a.cust_trans_id=b.cust_trans_id';
		$result['records']['defaulter_update'] = select_row($con, $s_defaulter);
		$result['count'] = count($result['records']['defaulter_update']);
		
		/*suspacious update - out of office hours operation*/
		$s_hours_condition = 'SELECT a.*, "suspacious_update", "out of office hours" AS updated_col, b.tans_date_time AS updated_value FROM `record_transactions` a, `record_transactions_dumped` b WHERE (HOUR(b.tans_date_time) < 7 OR HOUR(b.tans_date_time) > 21) AND a.cust_trans_id=b.cust_trans_id';
		$result['records']['out_of_office_hours'] = select_row($con, $s_hours_condition);
		$result['count'] += count($result['records']['out_of_office_hours']);
		
		/*suspacious update - FD amount > 12*income*/
		$s_fd_gt_income = 'SELECT a.*, "suspacious_update", "FD Amount > 12*Income" AS updated_col, b.amount AS updated_value FROM `record_transactions` a, `record_transactions_dumped` b WHERE  a.type_of_operation = "FD" AND b.amount > (12*a.salary_or_income) AND a.cust_trans_id=b.cust_trans_id';
		$result['records']['fd_gt_income'] = select_row($con, $s_fd_gt_income);
		$result['count'] += count($result['records']['fd_gt_income']);
		
		/*suspacious update - salary updated month not March, April, May*/
		$s_salary_updated = 'SELECT a.*, "suspacious_update", concat("salary updated month - ", MONTHNAME(b.tans_date_time)) AS updated_col, b.salary_or_income AS updated_value FROM `record_transactions` a, `record_transactions_dumped` b WHERE b.salary_or_income <> "" AND MONTH(b.tans_date_time) NOT IN(3, 4, 5) AND b.customer_id IS NULL AND a.cust_trans_id=b.cust_trans_id';
		$result['records']['salary_updated'] = select_row($con, $s_salary_updated);
		$result['count'] += count($result['records']['salary_updated']);
		
		/*suspacious delete - Loan Pending*/
		$sd_loan_pending = 'SELECT a.*, "suspacious_delete", "loan_not_paid" AS updated_col, a.amount AS updated_value FROM `record_transactions` a, `record_transactions_dumped` b WHERE b.marked_deleted <> "" AND a.type_of_operation="Loan" AND a.amount > 0 AND b.customer_id IS NULL AND a.cust_trans_id=b.cust_trans_id';
		$result['records']['loan_not_paid'] = select_row($con, $sd_loan_pending);
		$result['count'] += count($result['records']['loan_not_paid']);
		
		/*suspacious delete - out of office time delete*/
		$sd_hours_condition = 'SELECT a.*, "suspacious_delete", "out of office hours" AS updated_col, a.amount AS updated_value FROM `record_transactions` a, `record_transactions_dumped` b WHERE b.marked_deleted <> "" AND (HOUR(b.marked_deleted) < 7 OR HOUR(b.marked_deleted) > 21) AND a.cust_trans_id=b.cust_trans_id';
		$result['records']['sd_out_of_office_hours'] = select_row($con, $sd_hours_condition);
		$result['count'] += count($result['records']['sd_out_of_office_hours']);
	/* } */
	
	return $result;
		
}

function get_current_data(){
	global $con;
	$org_table = 'record_transactions';
	$result = array('records' => array(), 'count'=>0);
	
	$data_query = 'select * from '.$org_table;
	$result['records'] = select_row($con, $data_query);
	$result['count'] = count($result['records']);
	return $result;
}

function get_dumped_data($restored){
	global $con;
	$dumped_table = 'record_transactions_dumped';
	$result = array('records' => array(), 'count'=>0);
	if($restored === true){
		$data_query = "select * from $dumped_table WHERE id < (SELECT id FROM record_transactions_dumped WHERE cust_trans_id='RESTORED')";
	}else{
		$data_query = "select * from $dumped_table";
	}
	$result['records'] = select_row($con, $data_query);
	$result['count'] = count($result['records']);
	return $result;
}

function check_restore(){
	global $con;
	$dumped_table = 'record_transactions_dumped';
	
	$res = select_row($con, "select * from $dumped_table where `cust_trans_id`='RESTORED'");
	if(!empty($res)){
		return true;
	}else{
		return false;
	}
}

function restore_data(){
	global $con;
	$org_table = 'record_transactions';
	$dumped_table = 'record_transactions_dumped';
	$recover_ids = isset($_POST['recover']) ? $_POST['recover'] : array();
	
	if(!empty($recover_ids)){
		$ids_in = implode(',', $_POST['recover']);
		$query = "UPDATE $dumped_table dest INNER JOIN $org_table src
				  ON dest.cust_trans_id = src.cust_trans_id
				  SET dest.customer_id = src.customer_id, dest.type_of_operation = src.type_of_operation , dest.amount =  src.amount, dest.salary_or_income = src.salary_or_income, dest.is_loan_defaulter=src.is_loan_defaulter, dest.tans_date_time = src.tans_date_time,
				  dest.marked_deleted = CASE WHEN dest.marked_deleted IS NOT NULL THEN src.tans_date_time END
				  WHERE src.cust_trans_id IN($ids_in)";
		if(mysqli_query($con, $query)){
			$date_now = Date('Y-m-d H:i:s');
			if(!check_restore()){
				mysqli_query($con, "INSERT INTO $dumped_table set `cust_trans_id`='RESTORED', `tans_date_time` = '$date_now'");
			}
			foreach($recover_ids as $rid){
				mysqli_query($con, "INSERT INTO $dumped_table set `cust_trans_id`=".$rid.", `tans_date_time` = '$date_now'");
			}
			echo "<script>location.href='cassandra_deletion_dynamic.php?action=restore&msg=success';</script>";
		}else{
			echo "<script>location.href='cassandra_deletion_dynamic.php?action=restore&msg=error';</script>";
		}
		
	}
}

?>