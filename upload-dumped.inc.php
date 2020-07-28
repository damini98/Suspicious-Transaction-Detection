<?php
$filePath = 'temp_upload_dumped.json';
$uploadTable = 'record_transactions_dumped';

/*include DB file*/
include('db.php');
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
				foreach($json->rows as $row){
					$row_type = $row->type;
					$row_pos = $row->position;
					$liveness = '';
					if(isset($row->liveness_info)){
						$liveness = $row->liveness_info->tstamp;
					}
					
					$query = "insert into $uploadTable set cust_trans_id='$part_key'";
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
							$cell_tstamp = $cell->tstamp;
							$query .= ", tans_date_time='$cell_tstamp'";
						}
						//print_r($cell);
						//echo "\n $part_key \t $part_pos \t $row_type \t $row_pos \t $liveness \t $cell_name \t $cell_val \t $cell_tstamp \n";
						
						$query .= ", $cell_name='$cell_val'";
					}
					//echo $query . PHP_EOL;
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
						
			if($err){
				unlink($filePath);
				header('Location: cassandra_deletion_dynamic.php?action=dump&msg=success');
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

?>