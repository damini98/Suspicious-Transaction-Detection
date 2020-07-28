<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forensics Analysis</title>
<link rel="stylesheet" type="text/css" href="css/style.css">

<style>
ul li.active{ background: #6b3447; }
.consolebody{ background: #000; color: #fff; padding: 10px; font-size:12px; }
.consolebody p{ margin: 5px 0px; padding: 5px; }
.hide{ display: none; }
.next-step{ display: inline !important; padding: 5px 30px; border: 1px solid darkgreen; border-radius: 4px; -webkit-box-shadow: inset 1px 6px 12px lightgreen, inset -1px -10px 5px darkgreen, 1px 2px 1px black; -moz-box-shadow: inset 1px 6px 12px lightgreen, inset -1px -10px 5px darkgreen, 1px 2px 1px black; box-shadow: inset 1px 6px 12px lightgreen, inset -1px -10px 5px  darkgreen, 1px 2px 1px black; background-color: green; color: white; text-shadow: 1px 1px 1px black; cursor: pointer; }
#dump table tr td, #dump table tr th{ border: 1px solid #ccc; }
</style>

</head>

<?php 
$action = isset($_GET['action']) ? $_GET['action'] : ""; 
include('upload.inc.php');

$check_restore_status = check_restore();

/*upload data option*/
if($action=='upload' && isset($_FILES['uploadFile'])){
	upload_data();
}

if($action=='dump' && isset($_FILES['uploadDumped'])){
	upload_dumped_data();
}

if($action=='find'){
	$s_records = find_suspacious_data();
}

if($action=='show'){
	$s_records = get_current_data();
}

if($action=='show_dump'){
	$s_records = get_dumped_data($check_restore_status);
}

if($action=='restore'){
	restore_data();
}


?>



<body>
	<table  class="wraper" border="0">
		<?php include('menu2.php'); ?>
		<tr width="100%">
			<?php include('left_sidebar_cassandra_deletion_2.php'); ?>
			<td  width="80%" height="505" valign="top" class="td_m" > 
				<h3>Analyze And Restore Data</h3>
				
				<!-- upload data action -->
				<?php if($action == 'upload'){ ?>
				<div id="upload">
					
					<?php 
					if(isset($_GET['msg'])){ 
						if($_GET['msg']=='success'){
							echo '<p style="color:green"><b>Data Uploaded Successfully</b></p>';
						}
					} 
					?>
					
					<form name="uploadData" action="" method="post" enctype="multipart/form-data">
						<label>Choose file to upload</label> <br/><br/>
						<input id="uploadFile" accept=".csv" type="file" name="uploadFile" required/><br/><br/>
						<button type="submit" class="next-step" id="uploadBtn">Upload</button>
					</form>
					
				</div>
				<?php } ?>
				
				<!-- current records action -->
				<?php if($action == 'show'){ ?>
				<div id="dump">
					<?php 
					if(isset($s_records['count']) && $s_records['count'] > 0){ 
						echo '<p style="color:green"><b>Found '.$s_records['count'].' transactions</b></p>';
					} 
					?>
					<table style="border-collapse: collapse" cellpadding="5">
						<thead>
							<tr>
								<th>Sr No</th>
								<th>cust_trans_id</th>
								<th>customer_id</th>
								<th>type_of_operation</th>
								<th>amount</th>
								<th>salary_or_income</th>
								<th>is_loan_defaulter</th>
								<th>tans_date_time</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($s_records['records'] as $k=>$r){ 
									 ?>
									<tr>
										<td><?php echo ($k+1) ?></td>
										<td><?php echo $r['cust_trans_id']; ?></td>
										<td><?php echo $r['customer_id']; ?></td>
										<td><?php echo $r['type_of_operation']; ?></td>
										<td><?php echo $r['amount']; ?></td>
										<td><?php echo $r['salary_or_income']; ?></td>
										<td><?php echo $r['is_loan_defaulter']; ?></td>
										<td><?php echo $r['tans_date_time']; ?></td>
									</tr>
								<?php } ?>
						</tbody>
					</table>
					<br/>
					<br/>
				</div>
				<?php } ?>
				
				<!-- upload dumped file action -->
				<?php if($action == 'dump'){ ?>
				<div id="dump">
					
					<?php 
					if(isset($_GET['msg'])){ 
						if($_GET['msg']=='success'){
							echo '<p style="color:green"><b>Logs Uploaded Successfully</b></p>';
						}
					} 
					?>
					
					<form name="uploadDumped" action="" method="post" enctype="multipart/form-data">
						<label>Choose file to upload</label> <br/><br/>
						<input id="uploadDumped" type="file" name="uploadDumped" required/><br/><br/>
						<button type="submit" class="next-step" id="uploadBtn">Upload</button>
					</form>
					
				</div>
				<?php } ?>
				
				<!-- current dumped records action -->
				<?php if($action == 'show_dump'){ ?>
				<div id="dump">
					<?php 
					if(isset($s_records['count']) && $s_records['count'] > 0){ 
						echo '<p style="color:green"><b>Found '.$s_records['count'].' transactions</b></p>';
					} 
					?>
					<table style="border-collapse: collapse" cellpadding="5">
						<thead>
							<tr>
								<th>Sr No</th>
								<th>cust_trans_id</th>
								<th>customer_id</th>
								<th>type_of_operation</th>
								<th>amount</th>
								<th>salary_or_income</th>
								<th>is_loan_defaulter</th>
								<th>tans_date_time</th>
								<th>marked_deleted</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($s_records['records'] as $k=>$r){ 
									 ?>
									<tr>
										<td><?php echo ($k+1) ?></td>
										<td><?php echo $r['cust_trans_id']; ?></td>
										<td><?php echo $r['customer_id']; ?></td>
										<td><?php echo $r['type_of_operation']; ?></td>
										<td><?php echo $r['amount']; ?></td>
										<td><?php echo $r['salary_or_income']; ?></td>
										<td><?php echo $r['is_loan_defaulter']; ?></td>
										<td><?php echo $r['tans_date_time']; ?></td>
										<td><?php echo $r['marked_deleted']; ?></td>
									</tr>
								<?php } ?>
						</tbody>
					</table>
					<br/>
					<br/>
				</div>
				<?php } ?>
				
				<!-- find supacious records action -->
				<?php if($action == 'find'){ ?>
				<div id="dump">
					
					<?php if(isset($s_records) && $s_records['count'] > 0){  ?>
					
					<?php if($check_restore_status){ ?>
						<p style="color:green"><b>There are some suspicious transactions restored already.</b></p>
						<a target="_blank" href="export.php"><button class="next-step" type="submit">Export Restored Data</button></a>
					<?php } ?>
					
					<p style="color:red"><b>Found following <?php echo $s_records['count']; ?> suspicious transactions/s</b></p>
						
					<form id="restore_data" action="cassandra_deletion_dynamic.php?action=restore" name="restore_data" method="post" onsubmit="return false;">
						
						<table style="border-collapse: collapse" cellpadding="5">
							<thead>
								<tr>
									<th colspan="9" style="color:green; text-align:center">Original Data</th>
									<th colspan="2" style="color:red; text-align:center">Log Table Data</th>
								</tr>
								<tr>
									<th><input type="checkbox" id="select_all" /></th>
									<th>Sr No</th>
									<th>cust_trans_id</th>
									<th>customer_id</th>
									<th>type_of_operation</th>
									<th>amount</th>
									<th>salary_or_income</th>
									<th>is_loan_defaulter</th>
									<th>tans_date_time</th>
									<th>type_of_suspect</th>
									<th>updated_value</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$k = 0;
									foreach($s_records['records'] as $records){ 
									 ?>
									<?php foreach($records as $r){ 
										$k++; ?>
										<tr>
											<td><input type="checkbox" name="recover[]" value="'<?php echo $r['cust_trans_id'];?>'" /></td>
											<td><?php echo ($k) ?></td>
											<td><?php echo $r['cust_trans_id']; ?></td>
											<td><?php echo $r['customer_id']; ?></td>
											<td><?php echo $r['type_of_operation']; ?></td>
											<td><?php echo $r['amount']; ?></td>
											<td><?php echo $r['salary_or_income']; ?></td>
											<td><?php echo $r['is_loan_defaulter']; ?></td>
											<td><?php echo $r['tans_date_time']; ?></td>
											<td><?php echo $r['updated_col']; ?></td>
											<td><?php echo $r['updated_value']; ?></td>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						 </table>
						 <br/>
						 <br/>
						 
						 <button class="next-step" type="submit">Restore Data</button>
						 
					   </form>
					<?php }else{ ?>
						<?php if($check_restore_status){ ?>
							<p style="color:green"><b>Suspicious transactions are restored already, there are no suspicious transactions found.</b></p>
							<a target="_blank" href="export.php"><button class="next-step" type="submit">Export Restored Data</button></a>
						<?php }else{ ?>
							<p style="color:green"><b>No suspicious transactions found.</b></p>
						<?php } ?>
					<?php } ?>
				</div>
				<?php } ?>
				
				<!-- find supacious records action -->
				<?php if($action == 'restore'){ ?>
				<div id="dump">
					
					<?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'){ ?>
						<p style="color:green"><b>Data restored successfully.</b></p>
						<?php if($check_restore_status){ ?>
						<a target="_blank" href="export.php"><button class="next-step" type="submit">Export Restored Data</button></a>
						<?php } ?>
					<?php } if(isset($_GET['msg']) && $_GET['msg'] == 'error'){ ?>
						<p style="color:red"><b>Error in restoring data.</b></p>
					<?php } ?>
				</div>
				<?php } ?>
				
				<!-- find supacious records action -->
				<?php if($action == 'export'){ ?>
				<div id="dump">
					<?php if(!$check_restore_status){ ?>
						<p style="color:red"><b>Please restore suspicious transactions if any and try again.</b></p>
					<?php } ?>
				</div>
				<?php } ?>
				
			</td>
		</tr>
		<tr class="tr_row">
			<td height="20" colspan="2" bgcolor="#9F6479" align="center"><span class="style11">Copyright &copy; 2019 College of Engineering, Pune</span></td>
		</tr>
	</table>
	
	<script src="jquery-3.2.1.min.js"></script>
	<script>
		$(function(){
			
			var csvdata = [];
			var fileInput = document.getElementById("uploadFile"),
			fileInput2 = document.getElementById("uploadDumped"),
			readFile = function () {
				var reader = new FileReader();
				reader.onload = function () {
					var lines = reader.result.split("\n");
					$.each(lines, function(k){
						if(lines[k]){
							var split_data = lines[k].split(",");
							if(split_data.length > 7 || split_data.length < 7){
								/*console.log(split_data);*/
								alert("File does not contain required columns");
								fileInput.value = '';
								return false;
							}
							/*csvdata.push([split_data[0], split_data[1]]);*/
						}
					});
			   };
			   reader.readAsBinaryString(fileInput.files[0]);
			};
			
			readFileJson = function () {
				var reader = new FileReader();
				reader.onload = function () {
					var lines = reader.result;
					console.log(lines);
					try{
						JSON.parse(lines);
					}catch(e){
						alert("Invalid JSON file");
						fileInput2.value = '';
					}
			   };
			   reader.readAsBinaryString(fileInput2.files[0]);
			};
			
			if(fileInput){
				fileInput.addEventListener('change', readFile);
			}
			if(fileInput2){
				fileInput2.addEventListener('change', readFileJson);
			}
			
			$('#restore_data button').click(function(){
				var f = document.restore_data;
				var checkboxes = document.getElementsByName('recover[]');
				var checked = $('input[type=checkbox]:checked');
				//console.log(checked.length);
				if(checked.length){
					f.submit();
				}
			});
			
			$('#select_all').click(function(){
				if($(this).is(':checked')){
					$('tbody input[type=checkbox]').prop('checked', true);
				}else{
					$('tbody input[type=checkbox]').prop('checked', false);
				}
			});
		});
		
	</script>
	
</body>
</html>
